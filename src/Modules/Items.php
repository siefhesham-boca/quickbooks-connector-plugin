<?php

namespace Bocapro\QuickbooksConnector\Modules;

use QuickBooksOnline\API\Facades\Item;
use Bocapro\QuickbooksConnector\Support\EntityRepository;

class Items extends EntityRepository
{
    protected function entity(): string
    {
        return 'Item';
    }

    protected function facade(): string
    {
        return Item::class;
    }

    /**
     * Return active items as an id => name map, suitable for a select.
     * Optionally filter by a Type (e.g. "Service", "Inventory", "NonInventory").
     *
     * @return array<string, string>
     */
    public function options(?string $type = null): array
    {
        $where = "WHERE Active = true";

        if ($type !== null) {
            $where .= " AND Type = '".addslashes($type)."'";
        }

        $options = [];

        foreach ($this->all($where) as $record) {
            $options[(string) $record->Id] = $record->FullyQualifiedName ?? $record->Name;
        }

        asort($options);

        return $options;
    }
}
