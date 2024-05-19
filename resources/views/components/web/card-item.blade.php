@props(['item'])
<article class="[&:not(:last-child)]:border-b border-gray-100 pb-10">
    <div class=" grid grid-cols-12 gap-3 mt-5 items-start">
        <div class=" col-span-4 flex items-center">
            <a href="">
                <img class="mw-100 mx-auto rounded-xl" src="{{ $item->getThumbnail() }}" alt="thumbnail">
            </a>
        </div>
        <div class="col-span-8">
            <div class="flex py-1 text-sm items-center justify-between">
                <div class="flex items-center">
                    <img class="w-7 h-7 rounded-full mr-3" src="{{ $item->author->getFilamentAvatarUrl() }}"
                        alt="avatar">
                    <span class="mr-1 text-xs">{{ $item->author->name }}</span>
                </div>
                <span class="text-gray-500 text-xs">{{ $item->published_at->diffForHumans() }}</span>
            </div>

            <h2 class="text-xl font-bold text-gray-900">
                <a href="{{ route('index.berita.show', $item->slug) }}" wire:navigate>
                    {{ $item->title }}
                </a>
            </h2>

            <x-markdown class="text-justify break-words line-clamp-4 mt-2 ">
                {{ $item->getExcerpt() }}
            </x-markdown>
            <div class=" mt-6 flex items-center justify-between">
                <div>
                    <x-filament::button wire:navigate
                        href="{{ route('index.berita', ['kategori' => $item->kategori->slug]) }}" tag="a">
                        {{ $item->kategori->name }}
                    </x-filament::button>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-500 text-sm">Perkiraan Waktu Membaca {{ $item->getReadingTime() }}</span>
                </div>
            </div>
        </div>
    </div>
</article>
