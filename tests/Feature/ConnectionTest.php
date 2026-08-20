<?php

use Bocapro\QuickbooksConnector\Facades\Qbo;
use Bocapro\QuickbooksConnector\Models\QuickbooksToken;
use Bocapro\QuickbooksConnector\Modules\CreditNotes;
use Bocapro\QuickbooksConnector\Modules\Customers;
use Bocapro\QuickbooksConnector\Modules\Invoices;
use Bocapro\QuickbooksConnector\Modules\Payments;
use Bocapro\QuickbooksConnector\Settings\QuickbooksSettings;

it('reports not connected when no token exists', function () {
    expect(Qbo::isConnected())->toBeFalse();
});

it('reports connected once a token is stored', function () {
    QuickbooksToken::create([
        'realm_id' => '123',
        'access_token' => 'access',
        'refresh_token' => 'refresh',
        'environment' => 'sandbox',
        'access_token_expires_at' => now()->addHour(),
    ]);

    expect(Qbo::isConnected())->toBeTrue();
});

it('resolves credentials from the settings class', function () {
    $settings = app(QuickbooksSettings::class);

    expect($settings->environment)->toBe('sandbox');

    $settings->client_id = 'my-client-id';
    $settings->client_secret = 'my-secret';
    $settings->save();

    expect(app(QuickbooksSettings::class)->client_id)->toBe('my-client-id')
        ->and(app(QuickbooksSettings::class)->client_secret)->toBe('my-secret');
});

it('uses the settings environment to scope connection state', function () {
    $settings = app(QuickbooksSettings::class);
    $settings->environment = 'production';
    $settings->save();

    QuickbooksToken::create([
        'realm_id' => '999',
        'access_token' => 'access',
        'refresh_token' => 'refresh',
        'environment' => 'sandbox',
        'access_token_expires_at' => now()->addHour(),
    ]);

    // A sandbox token must not count as connected while set to production.
    expect(Qbo::isConnected())->toBeFalse();

    $settings->environment = 'sandbox';
    $settings->save();

    expect(Qbo::isConnected())->toBeTrue();
});

it('exposes each supported module through the facade', function () {
    expect(Qbo::invoices())->toBeInstanceOf(Invoices::class)
        ->and(Qbo::payments())->toBeInstanceOf(Payments::class)
        ->and(Qbo::creditNotes())->toBeInstanceOf(CreditNotes::class)
        ->and(Qbo::customers())->toBeInstanceOf(Customers::class);
});

it('encrypts stored tokens at rest', function () {
    $token = QuickbooksToken::create([
        'realm_id' => '123',
        'access_token' => 'super-secret',
        'refresh_token' => 'refresh',
        'environment' => 'sandbox',
    ]);

    $raw = DB::table('quickbooks_tokens')->where('id', $token->id)->value('access_token');

    expect($raw)->not->toBe('super-secret')
        ->and($token->fresh()->access_token)->toBe('super-secret');
});
