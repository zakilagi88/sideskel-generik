<x-filament-panels::page>


    <div style="display: flex; justify-content: space-between;">
        <x-filament::breadcrumbs :breadcrumbs="[
            '/admin' => 'Beranda',
            '/admin/deskel-profil' => 'DesaKelurahan Profil',
        ]" />

        <div>
            {{ $this->getHeaderActions()['edit'] }}

            <x-filament-actions::modals />
        </div>
    </div>



    {{ $this->deskelInfolist }}


</x-filament-panels::page>
