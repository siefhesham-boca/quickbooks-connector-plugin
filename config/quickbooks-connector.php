<?php

use Bocapro\QuickbooksConnector\Models\QuickbooksToken;

// config for Bocapro/QuickbooksConnector

return [

    /*
    |--------------------------------------------------------------------------
    | QuickBooks Online OAuth2 Credentials
    |--------------------------------------------------------------------------
    |
    | The environment, client id and client secret are NOT configured here or
    | in your .env. They are entered by the user on the plugin's settings page
    | and persisted (secret encrypted) via spatie/laravel-settings. Only the
    | non-editable options below live in config.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Redirect
    |--------------------------------------------------------------------------
    |
    | The redirect URI is derived automatically from this package's callback
    | route (quickbooks-connector.callback) and shown on the settings page for
    | you to register on your Intuit app. It is intentionally not configurable.
    |
    */

    'scopes' => [
        'com.intuit.quickbooks.accounting',
    ],

    /*
    |--------------------------------------------------------------------------
    | Base URL & Minor Version
    |--------------------------------------------------------------------------
    */

    'base_url' => [
        'sandbox' => 'https://sandbox-quickbooks.api.intuit.com',
        'production' => 'https://quickbooks.api.intuit.com',
    ],

    'minor_version' => env('QBO_MINOR_VERSION', 75),

    /*
    |--------------------------------------------------------------------------
    | Token Storage
    |--------------------------------------------------------------------------
    |
    | The Eloquent model used to persist OAuth tokens and the connected
    | company (realm) id. Override to point at your own model if needed.
    |
    */

    'token_model' => QuickbooksToken::class,

    'table_name' => 'quickbooks_tokens',

    /*
    |--------------------------------------------------------------------------
    | Route Registration
    |--------------------------------------------------------------------------
    */

    'routes' => [
        'prefix' => 'quickbooks',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable the SDK request/response log and set where it writes.
    |
    */

    'log' => [
        'enabled' => env('QBO_LOG_ENABLED', false),
        'path' => storage_path('logs/quickbooks'),
    ],
];
