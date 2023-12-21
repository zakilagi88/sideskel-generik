<x-filament-panels::page>

    <form wire:submit="create">
        {{ $this->form }}

        <button type="submit" class="bg-blue-400 rounded-lg p-2 mt-4">
            kenapa tidak bisa
        </button>
    </form>
    <x-filament-actions::modals />
</x-filament-panels::page>
