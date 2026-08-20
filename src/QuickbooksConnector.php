<?php

namespace Bocapro\QuickbooksConnector;

use Bocapro\QuickbooksConnector\Modules\Accounts;
use Bocapro\QuickbooksConnector\Modules\CreditNotes;
use Bocapro\QuickbooksConnector\Modules\Customers;
use Bocapro\QuickbooksConnector\Modules\Invoices;
use Bocapro\QuickbooksConnector\Modules\Items;
use Bocapro\QuickbooksConnector\Modules\Payments;
use Bocapro\QuickbooksConnector\Support\QuickbooksConnection;

/**
 * Entry point resolved by the Qbo facade. Exposes one accessor per
 * supported QuickBooks module.
 */
class QuickbooksConnector
{
    public function __construct(protected QuickbooksConnection $connection) {}

    public function connection(): QuickbooksConnection
    {
        return $this->connection;
    }

    public function isConnected(): bool
    {
        return $this->connection->isConnected();
    }

    public function invoices(): Invoices
    {
        return new Invoices($this->connection);
    }

    public function payments(): Payments
    {
        return new Payments($this->connection);
    }

    public function creditNotes(): CreditNotes
    {
        return new CreditNotes($this->connection);
    }

    public function customers(): Customers
    {
        return new Customers($this->connection);
    }

    public function accounts(): Accounts
    {
        return new Accounts($this->connection);
    }

    public function items(): Items
    {
        return new Items($this->connection);
    }
}
