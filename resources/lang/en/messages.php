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
    'oauth' => [
        'connected' => 'QuickBooks company connected successfully.',
        'missing_params' => 'QuickBooks did not return an authorization code.',
    ],
];
