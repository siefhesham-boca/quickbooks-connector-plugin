<?php

use Bocapro\QuickbooksConnector\Models\QuickbooksToken;

// config for Bocapro/QuickbooksConnector

return [

    /*
    |--------------------------------------------------------------------------
    | QuickBooks Online OAuth2 Credentials
    |--------------------------------------------------------------------------
    |
    | These are issued when you create an app in the Intuit Developer portal
    | (https://developer.intuit.com). They may also be managed at runtime from
    | the plugin's settings page, which persists them to the tokens table.
    |
    */

    'client_id' => env('QBO_CLIENT_ID'),

    'client_secret' => env('QBO_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Either "sandbox" or "production". Determines which Intuit base URL the
    | SDK talks to.
    |
    */

    'environment' => env('QBO_ENVIRONMENT', 'sandbox'),

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
