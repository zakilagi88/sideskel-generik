<div>
    <div class="mb-6">
        <x-web.breadcrumbs :items="[$this->getPageBreadcrumb(), $this->getShiftPageBreadcrumb()]" :heading="$this->getPageHeading()" />
    </div>

    <div class="grid grid-cols-1 ">
        <div class="prose prose-2xl max-w-none prose-gray prose-h1:text-5xl">
            <h1 class="text-gray-900 ">{{ $record->title }}</h1>
            <img src="{{ $record->getThumbnail() }}" alt="{{ $record->slug }}"
                class=" rounded-lg shadow-lg object-fill mt-10 not-prose mx-auto">
            <div class="flex items-center space-x-8 text-sm mt-8">
                <div class="flex items-center gap-x-2">
                    <img src="{{ $record->author->getFilamentAvatarUrl() }}" alt="User Avatar"
                        class="h-8 w-8 flex-none rounded-full object-contain bg-gray-200">
                    {{ $record->author->name }}
                </div>
                <div class="flex items-center gap-x-1">
                    <x-heroicon-o-calendar class="w-4 h=4 text-gray-700" />
                    <p class="text-gray-700">{{ $record->published_at->format('F j, Y') }}</p>
                </div>
                <div
                    class="flex items-center gap-1 space-x-2 first-line:relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                    <x-heroicon-o-folder class="w-4 h-4 text-gray-700" />
                    <a href="{{ route('index.kategori_berita', $record->kategori->slug) }}"
                        class=" font-medium text-gray-600">
                        {{ str($record->kategori->name)->ucFirst() }}</a>
                </div>
            </div>

            <div class="flex items-center gap-1 mb-6 mt-4">
                <x-heroicon-o-hashtag class="w-4 h-4 text-gray-700" />
                @foreach ($record->tags as $tag)
                    @php
                        $colors = [
                            'bg-blue-300',
                            'bg-red-400',
                            'bg-green-300',
                            'bg-yellow-300',
                            'bg-indigo-400',
                            'bg-purple-400',
                            'bg-pink-400',
                            'bg-gray-400',
                        ];
                        $colorClass = $colors[array_rand($colors)];
                    @endphp
                    <span class="inline-block px-2 py-1 mr-2  text-sm font-semibold rounded-lg {{ $colorClass }}">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>

            <x-markdown class="berita-page text-justify">
                {!! $record->body !!}
            </x-markdown>
        </div>
    </div>
</div>
