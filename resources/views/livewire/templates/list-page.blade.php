<div class="w-full grid grid-cols-3 gap-6">
    <div class="col-span-full">
        <x-web.breadcrumbs :items="[$this->getPageBreadcrumb()]" :heading="$this->getPageHeading()" />
    </div>
    <div class="col-span-2 bg-white rounded-xl">
        <div id="posts" class=" px-3 lg:px-7 ">
            <div class="m-4">
                @if ($search)
                    <h3 class="text-lg font-semibold text-gray-600 mb-3">Sedang Mencari ... "{{ $search }}"</h3>
                @endif

                @if ($kategori)
                    <h3 class="text-lg font-semibold text-gray-600 mb-3">Kategori Berita: {{ $kategori }}</h3>
                @endif

                @if ($search || $kategori)
                    <div class="flex items-end space-x-4 font-light ">
                        <x-filament::button wire:navigate href="{{ route('index.berita') }}" icon="fas-xmark"
                            icon-position="after" tag="a">
                            Reset Pencarian
                        </x-filament::button>
                    </div>
                @endif

            </div>
            <div class="flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4 font-light ">
                    <button x-on:click="$wire.setSort('desc')"
                        :class="{
                            'text-gray-900 border-b border-gray-700 font-bold': '{{ $sort }}'
                            === 'desc',
                            'text-gray-500': '{{ $sort }}'
                            !== 'desc'
                        }"
                        class="py-4">Terkini
                    </button>
                    <button x-on:click="$wire.setSort('asc')"
                        :class="{
                            'text-gray-900 border-b border-gray-700 font-bold': '{{ $sort }}'
                            === 'asc',
                            'text-gray-500': '{{ $sort }}'
                            !== 'asc'
                        }"
                        class="py-4">Terlama
                    </button>
                </div>
            </div>
            <div class="py-4">
                @foreach ($this->records as $record)
                    <x-web.card-item :item="$record" />
                @endforeach
            </div>

            <div class="my-3">
                {{ $this->records->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    <div id="side-bar"
        class="bg-white rounded-xl border-t border-t-gray-100 md:border-t-none col-span-1 px-3 md:px-6  space-y-10 py-6 pt-10 md:border-l border-gray-100 h-screen">
        <div id="search-box">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Search</h3>
                <div class="py-2 px-3 mb-3 items-center">
                    <x-filament::input.wrapper>
                        <x-filament::input type="text" wire:model="search" wire:keydown.enter="searching" />
                        <x-slot name="suffix">
                            <x-filament::icon-button icon="fas-magnifying-glass" wire:click="searching"
                                label="New label" />
                        </x-slot>
                    </x-filament::input.wrapper>
                </div>

            </div>
        </div>
        <div id="recommended-topics-box">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Kategori Berita</h3>
            <div class="topics flex flex-wrap gap-4 justify-start">
                @foreach ($this->kategoris as $k)
                    <x-filament::button wire:navigate href="{{ route('index.berita', ['kategori' => $k->slug]) }}"
                        tag="a">
                        {{ $k->name }}
                    </x-filament::button>
                @endforeach
            </div>
        </div>
    </div>
</div>
