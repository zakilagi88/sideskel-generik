<x-filament-widgets::widget>
    <x-filament::section>
        <form wire:submit="save">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-3" form="save">
                {{ __('Simpan') }}
            </x-filament::button>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
