@props(['items', 'heading'])

<div class="bg-white shadow-md ring-gray-950/5 p-2 flex flex-col flex-wrap rounded-xl">
    <div class="pl-4 mb-2">
        <h1 class="text-2xl font-semibold  text-gray-950 dark:text-white">{{ ucwords($heading) }}</h1>
    </div>
    <ul class="flex items-center space-x-2 mb-1">
        <li class="inline-flex items-center">
            <x-filament::icon-button icon="fas-house" href="{{ route('index.beranda') }}" wire:navigate tag="a"
                class="mx-2" />

            <x-filament::icon icon="fas-chevron-right" class="size-3 text-gray-500 dark:text-gray-400" />
        </li>

        @foreach ($items as $item)
            @if (Route::has($item['routeName']))
                <li class="inline-flex items-center space-x-2">
                    @if (str_contains($item['routeName'], 'show'))
                        <a href="{{ route($item['routeName'], ['record' => $item['routeParameter']]) }}" wire:navigate
                            class="text-gray-600 hover:text-primary-500">
                            {{ ucwords($item['routeParameter']) }}
                        </a>
                    @else
                        <a href="{{ route($item['routeName']) ?: '#' }}" wire:navigate
                            class="text-gray-600 hover:text-primary-500">
                            {{ ucwords($item['routeParameter']) }}
                        </a>
                    @endif

                    @if (!$loop->last)
                        <x-filament::icon icon="fas-chevron-right" class="size-3 text-gray-500 dark:text-gray-400" />
                    @endif
                </li>
            @endif
        @endforeach
    </ul>


</div>
