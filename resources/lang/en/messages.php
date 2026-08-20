<?php

return [
    'settings' => [
        'title' => 'QuickBooks Online',
        'credentials' => 'Connection settings',
        'credentials_hint' => 'Configure your Intuit developer app credentials, then connect a company.',
        'environment' => 'Environment',
        'client_id' => 'Client ID',
        'client_secret' => 'Client secret',
        'redirect_uri' => 'Redirect URI',
        'redirect_uri_hint' => 'Copy this and add it as a Redirect URI on your Intuit app. It is fixed by this package and cannot be edited.',
        'copy' => 'Copy',
        'copied' => 'Redirect URI copied to clipboard.',
        'save' => 'Save settings',
        'saved' => 'QuickBooks settings saved.',
        'connect' => 'Connect to QuickBooks',
        'disconnect' => 'Disconnect',
        'disconnected' => 'QuickBooks company disconnected.',
        'status_connected' => 'Connected',
        'status_disconnected' => 'Not connected',
    ],
    'mappings' => [
        'title' => 'Default mappings',
        'hint' => 'Choose the default item and accounts used when pushing data to QuickBooks. These apply when a call does not specify its own.',
        'default_item' => 'Default item (invoice/credit note lines)',
        'default_income_account' => 'Default income account',
        'default_deposit_account' => 'Default deposit-to account (payments)',
        'none' => 'None',
    ],
    'oauth' => [
        'connected' => 'QuickBooks company connected successfully.',
        'missing_params' => 'QuickBooks did not return an authorization code.',
    ],
];
