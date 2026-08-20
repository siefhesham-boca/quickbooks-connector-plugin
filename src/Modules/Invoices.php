<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\Invoice;
use Bocapro\QuickbooksConnector\Support\EntityRepository;

class Invoices extends EntityRepository
{
    protected function entity(): string
    {
        return 'Invoice';
    }

    protected function facade(): string
    {
        return Invoice::class;
    }
}
