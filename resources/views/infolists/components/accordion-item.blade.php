<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @if (str_contains($getId(), 'tambahan'))
        <a href="{{ route('index.stat.tambahan.show', ['record' => $getKey()]) }}" wire:navigate
            class="relative  overflow-hidden transition-all duration-700 block">
            <div class="my-1 hover:rounded-2xl hover:mx-2 hover:bg-gray-100 transition-all duration-700">
                <p class="py-1 pl-6">{{ ucwords($getLabel()) }}</p>
            </div>
        </a>
    @else
        <a href="{{ route('index.stat.show', ['record' => $getKey()]) }}" wire:navigate
            class="relative  overflow-hidden transition-all duration-700 block">
            <div class="my-1 hover:rounded-2xl hover:mx-2 hover:bg-gray-100 transition-all duration-700">
                <p class="py-1 pl-6">{{ ucwords($getLabel()) }}</p>
            </div>
        </a>
    @endif
</x-dynamic-component>
