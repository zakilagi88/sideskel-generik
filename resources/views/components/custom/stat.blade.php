@props(['stat'])

<div class="lg:pt-12 pt-6 w-full md:w-4/12 lg:max-w-lg px-4 text-center">
    <div class="flex justify-around bg-gray-300 w-full mb-2 shadow-lg rounded-lg h-40 items-center gap-4">
        <div class="flex ml-4">
            <x-filament::icon :icon="$stat['icon']" class="h-20 w-20 text-{{ $stat['iconColor'] }} dark:text-gray-500" />
        </div>
        <div class="flex flex-col justify-center mr-4">
            <h4 class="text-2xl font-semibold mb-2">{{ $stat['heading'] }}</h4>
            <p class="text-gray-600 font-semibold text-4xl">{{ $stat['value'] }}</p>
        </div>
    </div>
</div>
