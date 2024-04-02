<div class="flex flex-row items-center m-auto bg-gray-100 p-6 rounded-md" style="height: 100px;">
    <div class="flex items-center justify-center gap-2">
        <div class=" rounded-full text-center flex items-center justify-center text-white bg-{{ $bgColor }}-400"
            style="width: 70px; height: 70px;">
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
