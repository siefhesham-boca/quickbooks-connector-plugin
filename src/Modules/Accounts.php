<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\Account;
use Bocapro\QuickbooksConnector\Support\EntityRepository;

class Accounts extends EntityRepository
{
    protected function entity(): string
    {
        return 'Account';
    }

    protected function facade(): string
    {
        return Account::class;
    }

    /**
     * Return active accounts as an id => name map, suitable for a select.
     * Optionally filter by an AccountType (e.g. "Bank", "Income", "Expense").
     *
     * @return array<string, string>
     */
    public function options(?string $accountType = null): array
    {
        $where = "WHERE Active = true";

        if ($accountType !== null) {
            $where .= " AND AccountType = '".addslashes($accountType)."'";
        }

        return $this->toOptions($this->all($where));
    }

    /**
     * @param  array<int, object>  $records
     * @return array<string, string>
     */
    protected function toOptions(array $records): array
    {
        $options = [];

        foreach ($records as $record) {
            $options[(string) $record->Id] = $record->FullyQualifiedName ?? $record->Name;
        }

        asort($options);

        return $options;
    }
}
