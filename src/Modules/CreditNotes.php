<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\CreditMemo;
use Bocapro\QuickbooksConnector\Support\EntityRepository;
use Bocapro\QuickbooksConnector\Support\LineDefaults;

class CreditNotes extends EntityRepository
{
    use LineDefaults;

    protected function entity(): string
    {
        return 'CreditMemo';
    }

    protected function facade(): string
    {
        return CreditMemo::class;
    }

    /**
     * Apply the configured default item to any sales lines that omit an
     * ItemRef before creating the credit note.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): object
    {
        return parent::create($this->applyDefaultItemToLines($attributes));
    }
}
