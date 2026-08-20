<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\Payment;
use Bocapro\QuickbooksConnector\Support\EntityRepository;

class Payments extends EntityRepository
{
    protected function entity(): string
    {
        return 'Payment';
    }

    protected function facade(): string
    {
        return Payment::class;
    }

    /**
     * Apply the configured default deposit-to account when the payload does
     * not specify one.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): object
    {
        $accountId = $this->settings()->default_deposit_account_id;

        if ($accountId !== null && ! isset($attributes['DepositToAccountRef'])) {
            $attributes['DepositToAccountRef'] = ['value' => $accountId];
        }

        return parent::create($attributes);
    }
}
