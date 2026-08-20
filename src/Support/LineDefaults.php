<?php

namespace Bocapro\QuickbooksConnector\Support;

/**
 * Fills in the configured default Item on transaction lines (invoices,
 * credit notes) that don't already reference one. A line is only touched
 * when it is a SalesItemLineDetail line missing its ItemRef.
 */
trait LineDefaults
{
    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function applyDefaultItemToLines(array $attributes): array
    {
        $itemId = $this->settings()->default_item_id;

        if ($itemId === null || empty($attributes['Line'])) {
            return $attributes;
        }

        foreach ($attributes['Line'] as $index => $line) {
            if (! is_array($line)) {
                continue;
            }

            $isSalesLine = ($line['DetailType'] ?? null) === 'SalesItemLineDetail'
                || isset($line['SalesItemLineDetail']);

            if (! $isSalesLine) {
                continue;
            }

            $detail = $line['SalesItemLineDetail'] ?? [];

            if (! isset($detail['ItemRef'])) {
                $detail['ItemRef'] = ['value' => $itemId];
                $line['SalesItemLineDetail'] = $detail;
                $line['DetailType'] ??= 'SalesItemLineDetail';
                $attributes['Line'][$index] = $line;
            }
        }

        return $attributes;
    }
}
