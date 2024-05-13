<x-filament-widgets::widget>
    <div class="bg-primary-400 rounded-3xl  mx-auto transition-all duration-500 ease-in-out">

        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-10 md:justify-center p-10" data-aos="fade-in">

            @foreach ($stats as $stat)
                <div
                    class="grid-cols-1 flex justify-start items-center bg-secondary-100 w-full shadow-lg rounded-lg p-10 gap-10">
                    <div class="flex ">
                        <x-filament::icon :icon="$stat['icon']" @class([
                            'h-20 w-20 dark:text-gray-500',
                            match ($stat['iconColor'] ?? null) {
                                'primary' => 'h-20 w-20 text-primary-400 ',
                                'secondary' => 'h-20 w-20 text-secondary-400 ',
                                'info' => 'h-20 w-20 text-info-400 ',
                                'warning' => 'h-20 w-20 text-warning-400 ',
                                'success' => 'h-20 w-20 text-success-400 ',
                                'danger' => 'h-20 w-20 text-danger-400 ',
                                default => 'h-20 w-20 text-gray-500 ',
                            },
                        ]) />
                    </div>
                    <div class="flex flex-col justify-center ">
                        <h4 class="text-2xl font-semibold mb-2">{{ $stat['heading'] }}</h4>
                        <p class="text-gray-600 font-semibold text-4xl">{{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-filament-widgets::widget>
