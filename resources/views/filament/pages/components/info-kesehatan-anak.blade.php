@props([
    'bgColor' => 'gray',
])

<div class="flex flex-row items-center m-auto ring-1 ring-gray-950/5 bg-white p-6 rounded-md" style="height: 100px;">
    <div class="flex items-center justify-center gap-2">
        <div @class([
            'rounded-full text-center flex items-center justify-center text-white',
            match ($bgColor) {
                'gray' => 'bg-gray-400 dark:bg-gray-500',
                default => 'bg-custom-500 dark:bg-custom-400',
            },
        ]) @style(['width: 70px; height: 70px;', \Filament\Support\get_color_css_variables($bgColor, shades: [400, 500]) => $bgColor !== 'gray'])>
            <div class="text-sm text-white">{{ $indeks }}</div>
        </div>
        <div class="flex flex-col">
            <div class="text-sm text-gray-700">Status Anak</div>
            <div class="text-sm text-gray-700">{{ ucwords(strtolower($status)) }}</div>
        </div>
    </div>
    <div class="flex items-center justify-center ml-auto">
        <div class="flex flex-col items-center justify-center">
            <div class="text-sm text-gray-700">Nilai Indeks</div>
            <div class="text-sm text-gray-700">{{ ucwords(strtolower($nilai)) }}</div>
        </div>
    </div>
</div>
