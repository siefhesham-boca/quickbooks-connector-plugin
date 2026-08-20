<?php

namespace Bocapro\QuickbooksConnector\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Bocapro\QuickbooksConnector\Facades\Qbo;
use Bocapro\QuickbooksConnector\Settings\QuickbooksSettings;

/**
 * @property-read Schema $form
 */
class QuickbooksSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'QuickBooks';

    protected static ?string $slug = 'quickbooks-settings';

    protected string $view = 'quickbooks-connector::pages.quickbooks-settings';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public function mount(): void
    {
        $settings = app(QuickbooksSettings::class);

        $this->form->fill([
            'environment' => $settings->environment,
            'client_id' => $settings->client_id,
            'client_secret' => $settings->client_secret,
            'redirect_uri' => Qbo::connection()->redirectUri(),
        ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Integrations';
    }

    public function getTitle(): string
    {
        return __('quickbooks-connector::messages.settings.title');
    }

    public function isConnected(): bool
    {
        return Qbo::isConnected();
    }

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->connectAction(),
            $this->disconnectAction(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('quickbooks-connector::messages.settings.credentials'))
                    ->description(__('quickbooks-connector::messages.settings.credentials_hint'))
                    ->schema([
                        Select::make('environment')
                            ->label(__('quickbooks-connector::messages.settings.environment'))
                            ->options([
                                'sandbox' => 'Sandbox',
                                'production' => 'Production',
                            ])
                            ->native(false)
                            ->required(),
                        TextInput::make('client_id')
                            ->label(__('quickbooks-connector::messages.settings.client_id'))
                            ->required(),
                        TextInput::make('client_secret')
                            ->label(__('quickbooks-connector::messages.settings.client_secret'))
                            ->password()
                            ->revealable()
                            ->required(),
                        TextInput::make('redirect_uri')
                            ->label(__('quickbooks-connector::messages.settings.redirect_uri'))
                            ->helperText(__('quickbooks-connector::messages.settings.redirect_uri_hint'))
                            ->readOnly()
                            ->dehydrated(false)
                            ->suffixAction(
                                Action::make('copy')
                                    ->icon(Heroicon::OutlinedClipboard)
                                    ->label(__('quickbooks-connector::messages.settings.copy'))
                                    ->action(function (): void {
                                        Notification::make()
                                            ->title(__('quickbooks-connector::messages.settings.copied'))
                                            ->success()
                                            ->send();
                                    })
                                    ->extraAttributes([
                                        'x-on:click' => 'window.navigator.clipboard.writeText($el.closest(\'.fi-input-wrp\').querySelector(\'input\').value)',
                                    ]),
                            ),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $settings = app(QuickbooksSettings::class);
        $settings->environment = $state['environment'];
        $settings->client_id = $state['client_id'];
        $settings->client_secret = $state['client_secret'];
        $settings->save();

        Notification::make()
            ->title(__('quickbooks-connector::messages.settings.saved'))
            ->success()
            ->send();
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label(__('quickbooks-connector::messages.settings.save'))
            ->icon(Heroicon::OutlinedCheck)
            ->submit('save');
    }

    public function connectAction(): Action
    {
        return Action::make('connect')
            ->label(__('quickbooks-connector::messages.settings.connect'))
            ->icon(Heroicon::OutlinedLink)
            ->color('primary')
            ->url(route('quickbooks-connector.connect'))
            ->visible(fn (): bool => ! $this->isConnected());
    }

    public function disconnectAction(): Action
    {
        return Action::make('disconnect')
            ->label(__('quickbooks-connector::messages.settings.disconnect'))
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn (): bool => $this->isConnected())
            ->action(function (): void {
                $environment = app(QuickbooksSettings::class)->environment;

                config('quickbooks-connector.token_model')::query()
                    ->where('environment', $environment)
                    ->delete();

                Notification::make()
                    ->title(__('quickbooks-connector::messages.settings.disconnected'))
                    ->success()
                    ->send();
            });
    }
}
