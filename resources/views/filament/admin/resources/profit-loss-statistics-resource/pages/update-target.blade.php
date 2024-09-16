<x-filament::page>
    <form wire:submit.prevent="updateTarget">
        <div class="space-y-4">
            {{ $this->form }}
        </div>

        <x-filament::button type="submit">
            Yangilash
        </x-filament::button>
    </form>
</x-filament::page>
