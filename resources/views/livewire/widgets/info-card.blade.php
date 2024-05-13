<x-filament-widgets::widget class="grid grid-cols-4 gap-2">
    @foreach ($stats as $k)
        <x-filament::section>
            <div class="flex flex-col items-center justify-center">
                <x-filament::icon icon="fas-tree" class="h-24 w-24 text-gray-500 dark:text-gray-400" />
                <p><strong>{{ $k }}</strong></p>
                <x-filament::button color="success">
                    Isi Potensi
                </x-filament::button>
            </div>
        </x-filament::section>
    @endforeach
</x-filament-widgets::widget>
