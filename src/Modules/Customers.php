<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\Customer;
use Bocapro\QuickbooksConnector\Support\EntityRepository;

class Customers extends EntityRepository
{
    protected function entity(): string
    {
        return 'Customer';
    }

    protected function facade(): string
    {
        return Customer::class;
    }
}
