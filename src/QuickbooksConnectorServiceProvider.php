<?php

namespace Bocapro\QuickbooksConnector;

use Bocapro\QuickbooksConnector\Settings\QuickbooksSettings;
use Bocapro\QuickbooksConnector\Support\QuickbooksConnection;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QuickbooksConnectorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'quickbooks-connector';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web')
            ->hasMigration('create_quickbooks_tokens_table');
    }

    public function packageRegistered(): void
    {
        $this->registerSettings();

        $this->app->singleton(QuickbooksConnection::class, function ($app): QuickbooksConnection {
            return new QuickbooksConnection(
                fn (): QuickbooksSettings => $app->make(QuickbooksSettings::class),
                config('quickbooks-connector'),
            );
        });

        $this->app->singleton(QuickbooksConnector::class, function ($app): QuickbooksConnector {
            return new QuickbooksConnector($app->make(QuickbooksConnection::class));
        });
    }

    public function packageBooted(): void
    {
        // Ensure the settings migration is discoverable by Laravel's migrator
        // regardless of the order this provider boots relative to
        // spatie/laravel-settings.
        $this->loadMigrationsFrom($this->package->basePath('/../database/settings'));
    }

    /**
     * Register the settings class and its migration path with
     * spatie/laravel-settings so it is discoverable and migratable.
     */
    protected function registerSettings(): void
    {
        config()->set('settings.settings', array_values(array_unique(array_merge(
            config('settings.settings', []),
            [QuickbooksSettings::class],
        ))));

        $migrationPath = $this->package->basePath('/../database/settings');

        config()->set('settings.migrations_paths', array_values(array_unique(array_merge(
            config('settings.migrations_paths', []),
            [$migrationPath],
        ))));
    }
}
