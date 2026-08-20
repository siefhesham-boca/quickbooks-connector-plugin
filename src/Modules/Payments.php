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
}
