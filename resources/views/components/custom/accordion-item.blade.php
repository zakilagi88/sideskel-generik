<!-- resources/views/components/AccordionItem.blade.php -->
@props(['stat'])
<div>
    <li class="bg-white border-solid border-[1.5px] border-gray-200 my-2 rounded-xl">
        <button type="button" class="w-full px-8 py-1 h-9 text-left"
            @click="selected = selected !== {{ $stat['id'] }} ? {{ $stat['id'] }} : null">
            <div class="flex items-center justify-between">
                <span>{{ $stat['nama'] }}</span>
                <x-filament::icon icon="fas-chevron-up"
                    class="h-4 w-4 text-primary dark:text-gray-500 transform transition-transform duration-700"
                    x-bind:class="{ 'rotate-180': selected == {{ $stat['id'] }} }" />
            </div>
        </button>
        <div class="border-b border-gray-200 " x-show="selected == {{ $stat['id'] }}"
            style="transition: transform 0.3s ease; transform: translateX(0%);">
        </div>
        @foreach ($stat->stats as $komponen)
            <a href="{{ route('stat.display', $komponen['slug']) }}"
                class="relative  overflow-hidden transition-all max-h-0 duration-700 block" style=""
                x-ref="container{{ $komponen['id'] }}"
                x-bind:style="selected == {{ $stat['id'] }} ? 'max-height: ' + $refs.container{{ $komponen['id'] }}
                    .scrollHeight +
                    'px' :
                    ''">
                <div class="my-1 hover:rounded-2xl hover:mx-2 hover:bg-gray-100 transition-all duration-700">
                    <p class="py-1 pl-6">{{ $komponen['nama'] }}</p>
                </div>
            </a>
        @endforeach
    </li>
</div>
