<x-filament-panels::page>

    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/penduduk' => 'Penduduk',
        '/admin/penduduk-stats' => 'Statistik',
    ]" />

    <section class="pb-20 space-y-8 sm:pb-20">
        <div style='width:100%;' class="container  left-0 flex flex-row items-stretch  w-full max-w-full space-x-12"
            x-data="{ tab: 1 }">
            @include('filament.pages.components.left-sidebar')
            <livewire:pages.stats-grid />
        </div>
    </section>

</x-filament-panels::page>
