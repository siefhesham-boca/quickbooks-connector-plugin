<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\CreditMemo;
use Bocapro\QuickbooksConnector\Support\EntityRepository;

class CreditNotes extends EntityRepository
{
    protected function entity(): string
    {
        return 'CreditMemo';
    }

    protected function facade(): string
    {
        return CreditMemo::class;
    }
}
