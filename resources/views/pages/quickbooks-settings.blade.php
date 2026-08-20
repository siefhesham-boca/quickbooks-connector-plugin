<x-filament-panels::page>
    <div>
        @if ($this->isConnected())
            <x-filament::badge color="success" icon="heroicon-o-check-circle">
                {{ __('quickbooks-connector::messages.settings.status_connected') }}
            </x-filament::badge>
        @else
            <x-filament::badge color="gray" icon="heroicon-o-x-circle">
                {{ __('quickbooks-connector::messages.settings.status_disconnected') }}
            </x-filament::badge>
        @endif
    </div>

    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-end">
            {{ $this->saveAction }}
        </div>
    </form>
</x-filament-panels::page>
