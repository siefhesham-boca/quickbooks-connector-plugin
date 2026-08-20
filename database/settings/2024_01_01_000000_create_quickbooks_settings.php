<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('quickbooks.environment', config('quickbooks-connector.environment', 'sandbox'));
        $this->migrator->add('quickbooks.client_id', config('quickbooks-connector.client_id'));
        $this->migrator->addEncrypted('quickbooks.client_secret', config('quickbooks-connector.client_secret'));
    }

    public function down(): void
    {
        $this->migrator->delete('quickbooks.environment');
        $this->migrator->delete('quickbooks.client_id');
        $this->migrator->delete('quickbooks.client_secret');
    }
};
