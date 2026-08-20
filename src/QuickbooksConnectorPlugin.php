<?php

namespace Bocapro\QuickbooksConnector;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Bocapro\QuickbooksConnector\Pages\QuickbooksSettingsPage;

class QuickbooksConnectorPlugin implements Plugin
{
    protected bool $hasSettingsPage = true;

    public function getId(): string
    {
        return 'quickbooks-connector';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function settingsPage(bool $condition = true): static
    {
        $this->hasSettingsPage = $condition;

        return $this;
    }

    public function hasSettingsPage(): bool
    {
        return $this->hasSettingsPage;
    }

    public function register(Panel $panel): void
    {
        if ($this->hasSettingsPage) {
            $panel->pages([
                QuickbooksSettingsPage::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
