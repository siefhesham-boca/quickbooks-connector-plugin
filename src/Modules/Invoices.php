<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\Invoice;
use Bocapro\QuickbooksConnector\Support\EntityRepository;
use Bocapro\QuickbooksConnector\Support\LineDefaults;

class Invoices extends EntityRepository
{
    use LineDefaults;

    protected function entity(): string
    {
        return 'Invoice';
    }

    protected function facade(): string
    {
        return Invoice::class;
    }

    /**
     * Apply the configured default item to any sales lines that omit an
     * ItemRef before creating the invoice.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): object
    {
        return parent::create($this->applyDefaultItemToLines($attributes));
    }
}
