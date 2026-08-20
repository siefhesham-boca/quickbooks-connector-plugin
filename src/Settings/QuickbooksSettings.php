<?php

namespace Bocapro\QuickbooksConnector\Settings;

use Spatie\LaravelSettings\Settings;

class QuickbooksSettings extends Settings
{
    public string $environment;

    public ?string $client_id;

    public ?string $client_secret;

    /**
     * Default QuickBooks Item id used for invoice/credit-note lines when the
     * caller does not supply one.
     */
    public ?string $default_item_id;

    /**
     * Default income Account id.
     */
    public ?string $default_income_account_id;

    /**
     * Default deposit-to (bank / undeposited funds) Account id used when
     * recording payments.
     */
    public ?string $default_deposit_account_id;

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
