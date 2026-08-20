<?php

namespace Bocapro\QuickbooksConnector\Settings;

use Spatie\LaravelSettings\Settings;

class QuickbooksSettings extends Settings
{
    public string $environment;

    public ?string $client_id;

    public ?string $client_secret;

    public static function group(): string
    {
        return 'quickbooks';
    }

    /**
     * The client secret is sensitive and stored encrypted at rest.
     *
     * @return array<int, string>
     */
    public static function encrypted(): array
    {
        return [
            'client_secret',
        ];
    }
}
