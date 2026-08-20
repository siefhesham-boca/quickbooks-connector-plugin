<?php

namespace Bocapro\QuickbooksConnector\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Bocapro\QuickbooksConnector\Support\QuickbooksConnection;

class QuickbooksOAuthController extends Controller
{
    public function __construct(protected QuickbooksConnection $connection) {}

    /**
     * Kick off the Intuit OAuth2 authorization flow.
     */
    public function connect(): RedirectResponse
    {
        $authUrl = $this->connection
            ->authorizationService()
            ->getOAuth2LoginHelper()
            ->getAuthorizationCodeURL();

        return redirect()->away($authUrl);
    }

    /**
     * Handle the redirect back from Intuit, exchange the code for tokens
     * and persist them.
     */
    public function callback(Request $request): RedirectResponse
    {
        $code = $request->query('code');
        $realmId = $request->query('realmId');

        if (! $code || ! $realmId) {
            return $this->redirectToSettings(
                error: __('quickbooks-connector::messages.oauth.missing_params'),
            );
        }

        $helper = $this->connection->authorizationService()->getOAuth2LoginHelper();

        $token = $helper->exchangeAuthorizationCodeForToken($code, $realmId);

        $environment = $this->connection->currentSettings()->environment;
        $model = config('quickbooks-connector.token_model');

        $model::query()->where('environment', $environment)->delete();

        $model::query()->create([
            'realm_id' => $realmId,
            'access_token' => $token->getAccessToken(),
            'refresh_token' => $token->getRefreshToken(),
            'access_token_expires_at' => $this->expiresAt($token->getAccessTokenExpiresAt()),
            'refresh_token_expires_at' => $this->expiresAt($token->getRefreshTokenExpiresAt()),
            'environment' => $environment,
        ]);

        return $this->redirectToSettings(
            success: __('quickbooks-connector::messages.oauth.connected'),
        );
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

    protected function redirectToSettings(?string $success = null, ?string $error = null): RedirectResponse
    {
        if ($success) {
            session()->flash('quickbooks-connector::success', $success);
        }

        if ($error) {
            session()->flash('quickbooks-connector::error', $error);
        }

        $url = url(config('quickbooks-connector.routes.prefix') === null ? '/' : '/admin');

        return redirect()->to(url()->previous() ?: $url);
    }
}
