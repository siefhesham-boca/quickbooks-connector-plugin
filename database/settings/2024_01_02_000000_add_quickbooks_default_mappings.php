<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('quickbooks.default_item_id', null);
        $this->migrator->add('quickbooks.default_income_account_id', null);
        $this->migrator->add('quickbooks.default_deposit_account_id', null);
    }

    public function down(): void
    {
        $this->migrator->delete('quickbooks.default_item_id');
        $this->migrator->delete('quickbooks.default_income_account_id');
        $this->migrator->delete('quickbooks.default_deposit_account_id');
    }
};
