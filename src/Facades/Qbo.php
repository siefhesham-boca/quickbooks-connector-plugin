<?php

namespace Bocapro\QuickbooksConnector\Facades;

use Illuminate\Support\Facades\Facade;
use Bocapro\QuickbooksConnector\QuickbooksConnector;
use Bocapro\QuickbooksConnector\Modules\Accounts;
use Bocapro\QuickbooksConnector\Modules\CreditNotes;
use Bocapro\QuickbooksConnector\Modules\Customers;
use Bocapro\QuickbooksConnector\Modules\Invoices;
use Bocapro\QuickbooksConnector\Modules\Items;
use Bocapro\QuickbooksConnector\Modules\Payments;
use Bocapro\QuickbooksConnector\Support\QuickbooksConnection;

/**
 * @method static bool isConnected()
 * @method static QuickbooksConnection connection()
 * @method static Invoices invoices()
 * @method static Payments payments()
 * @method static CreditNotes creditNotes()
 * @method static Customers customers()
 * @method static Accounts accounts()
 * @method static Items items()
 *
 * @see QuickbooksConnector
 */
class Qbo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return QuickbooksConnector::class;
    }
}
