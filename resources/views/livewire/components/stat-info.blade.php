<x-filament-widgets::widget>
    <div class="bg-primary-400 rounded-3xl  mx-auto transition-all duration-500 ease-in-out">

        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-10 md:justify-center p-10" data-aos="fade-in">

            @foreach ($stats as $stat)
                <div
                    class="grid-cols-1 flex justify-start items-center bg-secondary-100 w-full shadow-lg rounded-lg p-6 lg:p-10 gap-6">
                    <div class="flex ">
                        <x-filament::icon :icon="$stat['icon']" @class([
                            'size-12 sm:size-16 dark:text-gray-500',
                            match ($stat['iconColor'] ?? null) {
                                'primary' => 'text-primary-400 ',
                                'secondary' => 'text-secondary-400 ',
                                'info' => 'text-info-400 ',
                                'warning' => 'text-warning-400 ',
                                'success' => 'text-success-400 ',
                                'danger' => 'text-danger-400 ',
                                default => 'text-gray-400 ',
                            },
                        ]) />
                    </div>
                    <div class="flex flex-col justify-center ">
                        <h4 class="text-xl sm:text-2xl lg:text-3xl font-semibold mb-2">{{ $stat['heading'] }}
                        </h4>
                        <p class="text-2xl sm:text-3xl lg:text-4xl text-gray-600 font-semibold ">
                            {{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
