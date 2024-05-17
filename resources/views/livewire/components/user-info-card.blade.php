<div class=" gap-2 mt-2 flex items-center justify-center" x-show="$store.sidebar.isOpen">
    <div class="bg-transparent relative overflow-hidden rounded-xl p-1">
        <div class="flex justify-between items-center">
            <img src="{{ Auth::user()->getFilamentAvatarUrl() }}"
                class="size-16 object-center object-cover rounded-xl mr-8" />
            <div class="w-fit">
                <div class="flex flex-row items-center gap-2">
                    <x-filament::icon icon="fas-user" class="size-3 text-primary-400 dark:text-primary-400" />
                    <p class="text-xs text-primary-400 dark:text-primary-400 font-semibold">
                        {{ Auth::user()->name }}</p>
                </div>
                <h2 class="text-gray-400 dark:text-gray-300 font-bold text-sm">
                    {{ $data['sebutan_deskel'] . ' ' . ucwords(strtolower($deskel->dk?->deskel_nama)) }}</h2>
                <p class="text-xs text-gray-400">
                    {{ $data['singkatan_kabkota'] . ' ' . ucwords(strtolower(str_replace('KOTA ', '', $deskel->kabkota?->kabkota_nama))) }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ $data['singkatan_kec'] . ' ' . ucwords(strtolower($deskel->kec?->kec_nama)) }}</p>

            </div>
        </div>
    </div>
</div>
