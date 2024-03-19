<x-filament::fieldset>
    <x-slot name="label">
        Informasi Wilayah
    </x-slot>
    <div class="grid grid-cols-2 gap-1">
        <div class="mb-2">
            <span class="text-gray-600 text-sm font-bold">Provinsi:</span>
            <span class="text-gray-800 text-sm font-semibold block">{{ ucwords(strtolower($prov_nama)) }}</span>
        </div>
        <div class="mb-2">
            <span class="text-gray-600 text-sm font-bold">Kabupaten/Kota:</span>
            <span class="text-gray-800 text-sm font-semibold block">{{ ucwords(strtolower($kabkota_nama)) }}</span>
        </div>
        <div class="mb-2">
            <span class="text-gray-600 text-sm font-bold">Kecamatan:</span>
            <span class="text-gray-800 text-sm font-semibold block">{{ ucwords(strtolower($kec_nama)) }}</span>
        </div>
        <div class="mb-2">
            <span class="text-gray-600 text-sm font-bold">Kelurahan:</span>
            <span class="text-gray-800 text-sm font-semibold block">{{ ucwords(strtolower($deskel_nama)) }}</span>
        </div>

    </div>
</x-filament::fieldset>
