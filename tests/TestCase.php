<?php

namespace Bocapro\QuickbooksConnector\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Bocapro\QuickbooksConnector\QuickbooksConnectorServiceProvider;
use Spatie\LaravelSettings\LaravelSettingsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Bocapro\\QuickbooksConnector\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelSettingsServiceProvider::class,
            QuickbooksConnectorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }

    /**
     * Runs after the application and database connection are booted.
     */
    protected function defineDatabaseMigrations(): void
    {
        $tokensMigration = include __DIR__.'/../database/migrations/create_quickbooks_tokens_table.php.stub';
        $tokensMigration->up();

        // spatie/laravel-settings stores its values in a table created by its
        // own package migration; create it, then run the package's settings
        // migration to seed defaults.
        $settingsTable = include __DIR__.'/../vendor/spatie/laravel-settings/database/migrations/create_settings_table.php.stub';
        $settingsTable->up();

        $settingsMigration = include __DIR__.'/../database/settings/2024_01_01_000000_create_quickbooks_settings.php';
        $settingsMigration->up();
    }
}
