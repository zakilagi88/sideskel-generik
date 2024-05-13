<x-filament-panels::page>

    <x-filament::breadcrumbs :breadcrumbs="[
        '/panel' => 'Beranda',
        '/panel/desa/keamanan-dan-lingkungan' => 'Keamanan dan Lingkungan',
    ]" />

    <x-filament-panels::form wire:submit="save">
        {{ $this->extraForm }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    <x-filament-panels::page.unsaved-data-changes-alert />

</x-filament-panels::page>
