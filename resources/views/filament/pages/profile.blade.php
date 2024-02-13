<x-filament-panels::page>
    <div class="max-w-5xl">
        <x-filament-panels::form wire:submit="update">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </x-filament-panels::form>
    </div>
</x-filament-panels::page>
