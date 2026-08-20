<?php

namespace Bocapro\QuickbooksConnector\Support;

use Illuminate\Support\Carbon;
use QuickBooksOnline\API\DataService\DataService;
use RuntimeException;
use Bocapro\QuickbooksConnector\Models\QuickbooksToken;
use Bocapro\QuickbooksConnector\Settings\QuickbooksSettings;

/**
 * Resolves a ready-to-use QuickBooks Online DataService instance for the
 * currently connected company, transparently refreshing the access token
 * when it has expired.
 *
 * Credentials (environment, client id/secret, redirect uri) come from the
 * spatie/laravel-settings backed {@see QuickbooksSettings}, while non-editable
 * options (scopes, minor version, token model, logging) come from config.
 */
class QuickbooksConnection
{
    protected ?DataService $dataService = null;

    /**
     * @param  (\Closure(): QuickbooksSettings)  $settingsResolver
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected \Closure $settingsResolver,
        protected array $config,
    ) {}

    protected function settings(): QuickbooksSettings
    {
        return ($this->settingsResolver)();
    }

    /**
     * Build a DataService for the OAuth authorization flow (no tokens yet).
     */
    public function authorizationService(): DataService
    {
        $settings = $this->settings();

        return DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $settings->client_id,
            'ClientSecret' => $settings->client_secret,
            'RedirectURI' => $this->redirectUri(),
            'scope' => implode(' ', $this->config['scopes']),
            'baseUrl' => $settings->environment,
        ]);
    }

    /**
     * The OAuth2 redirect URI is fixed by this package's callback route and
     * must be registered verbatim on the Intuit app. It is never editable.
     */
    public function redirectUri(): string
    {
        return route('quickbooks-connector.callback');
    }

    /**
     * Return an authenticated DataService for the stored company, refreshing
     * the access token first if it is expired.
     */
    public function dataService(): DataService
    {
        if ($this->dataService instanceof DataService) {
            return $this->dataService;
        }

        $token = $this->token();

        if ($token->accessTokenHasExpired()) {
            $token = $this->refresh($token);
        }

        return $this->dataService = $this->makeDataService($token);
    }

    protected function makeDataService(QuickbooksToken $token): DataService
    {
        $settings = $this->settings();

        $service = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $settings->client_id,
            'ClientSecret' => $settings->client_secret,
            'accessTokenKey' => $token->access_token,
            'refreshTokenKey' => $token->refresh_token,
            'QBORealmID' => $token->realm_id,
            'baseUrl' => $token->environment,
        ]);

        $service->setMinorVersion((string) $this->config['minor_version']);

        if ($this->config['log']['enabled']) {
            $service->setLogLocation($this->config['log']['path']);
            $service->enableLog();
        } else {
            $service->disableLog();
        }

        return $service;
    }

    protected function refresh(QuickbooksToken $token): QuickbooksToken
    {
        $service = $this->makeDataService($token);
        $oauth = $service->getOAuth2LoginHelper();

        $refreshed = $oauth->refreshToken();

        $token->update([
            'access_token' => $refreshed->getAccessToken(),
            'refresh_token' => $refreshed->getRefreshToken(),
            'access_token_expires_at' => $this->expiresAt($refreshed->getAccessTokenExpiresAt()),
        ]);

        // Reset cached service so it is rebuilt with the fresh token.
        $this->dataService = null;

        return $token->refresh();
    }

    /**
     * Normalise the expiry value returned by the Intuit SDK into a Carbon
     * instance. Depending on SDK version this is either an absolute datetime
     * string (e.g. "2026-08-20 08:12:41") or a unix timestamp — never a
     * relative "seconds from now" duration.
     */
    protected function expiresAt(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp((int) $value);
        }

        return Carbon::parse($value);
    }

    protected function token(): QuickbooksToken
    {
        $token = $this->tokenModel()::query()
            ->where('environment', $this->settings()->environment)
            ->latest()
            ->first();

        if (! $token) {
            throw new RuntimeException(
                'No QuickBooks Online connection found. Connect a company from the QuickBooks settings page first.'
            );
        }

        return $token;
    }

    public function isConnected(): bool
    {
        return $this->tokenModel()::query()
            ->where('environment', $this->settings()->environment)
            ->exists();
    }

    public function currentSettings(): QuickbooksSettings
    {
        return $this->settings();
    }

    /**
     * @return class-string<QuickbooksToken>
     */
    protected function tokenModel(): string
    {
        return $this->config['token_model'];
    }
}
