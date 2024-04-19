<div class="py-24 sm:py-32">
    {{ dd($berita) }}
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto grid grid-cols-1 ">
            <div class="mx-auto prose prose-gray sm:prose-sm md:prose-2xl lg:prose-2xl prose-h1:text-5xl">
                <h1 class="text-gray-900 ">{{ $berita->title }}</h1>
                <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->slug }}"
                    class=" rounded-lg shadow-lg object-fill mt-10 not-prose">
                <div class="flex items-center space-x-8 text-sm mt-8">
                    <div class="flex items-center gap-x-2">
                        <img src="{{ asset($berita->user->getFilamentAvatarUrl()) }}" alt="User Avatar"
                            class="h-8 w-8 flex-none rounded-full object-contain bg-gray-200">
                        {{ $berita->user->name }}
                    </div>
                    <div class="flex items-center gap-x-1">
                        <x-heroicon-o-calendar class="w-4 h=4 text-gray-700" />
                        <p class="text-gray-700">{{ $berita->published_at->format('F j, Y') }}</p>
                    </div>
                    <div
                        class="flex items-center gap-1 space-x-2 first-line:relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                        <x-heroicon-o-folder class="w-4 h-4 text-gray-700" />
                        <a href="{{ route('kategori_berita', $berita->kategori->slug) }}"
                            class=" font-medium text-gray-600">
                            {{ str($berita->kategori->name)->ucFirst() }}</a>
                    </div>
                </div>

                <div class="flex items-center gap-1 mb-6 mt-4">
                    <x-heroicon-o-hashtag class="w-4 h-4 text-gray-700" />
                    @foreach ($berita->tags as $tag)
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
                    {!! $berita->body !!}
                </x-markdown>
            </div>
        </div>
    </div>

</div>
