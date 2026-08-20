<?php

namespace Bocapro\QuickbooksConnector\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
            'access_token_expires_at' => now()->addSeconds(
                (int) $token->getAccessTokenExpiresAt() - time()
            ),
            'refresh_token_expires_at' => now()->addSeconds(
                (int) $token->getRefreshTokenExpiresAt() - time()
            ),
            'environment' => $environment,
        ]);

        return $this->redirectToSettings(
            success: __('quickbooks-connector::messages.oauth.connected'),
        );
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
