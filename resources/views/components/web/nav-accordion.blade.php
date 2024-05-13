@props(['items', 'key'])
@if (!empty($items['submenu']))
    <li class="border-l-2 border-secondary-100 text-white px-4">
        <div class="flex flex-row w-full"
            x-on:click="selected !== {{ $key }} ? selected = {{ $key }} : selected = null">
            <x-web.nav-link :items="$items" />

            <x-filament::icon icon="fas-chevron-up"
                class="size-3 mt-3 ml-2 text-white dark:text-primary-400 transform transition-transform duration-700"
                x-bind:class="{ 'rotate-180': selected == {{ $key }} }" />
        </div>
        <!-- Submenu starts -->
        <div class="relative overflow-hidden max-h-0 transition-all duration-700" style=""
            x-ref="subMenu{{ $key }}"
            x-bind:style="selected == {{ $key }} ? 'max-height: ' + $refs.subMenu{{ $key }}.scrollHeight + 'px' : ''">
            @foreach ($items['submenu'] as $sub)
                <div class="hover:bg-secondary-100 hover:text-primary-400">
                    <x-web.nav-link :items="$sub" />

                </div>
            @endforeach
        </div>
        <!-- Submenu ends -->
    </li>
@else
    <li class="border-l-2 border-secondary-100 hover:bg-secondary-100 text-white hover:text-primary-400 px-4 ">
        <x-web.nav-link :items="$items" />
    </li>
@endif
