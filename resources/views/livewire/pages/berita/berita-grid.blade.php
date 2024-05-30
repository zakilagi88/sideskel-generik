            <div>
                <div class="w-full">
                    @foreach ($beritas as $b)
                        @if ($loop->first)
                            <div class="flex flex-col lg:flex-row bg-transparent dark:bg-gray-800 dark:text-gray-50">
                                <div class="w-full flex lg:hidden">
                                    <img src="{{ $b->getThumbnail() }}" alt="{{ $b->slug }}"
                                        class="w-full h-72 object-cover rounded-lg ">
                                </div>
                                <div class="w-full lg:w-3/5 flex flex-col pt-4 lg:pr-10">
                                    <div class="flex items-center gap-x-4 text-xs">
                                        <time datetime="2020-03-16"
                                            class="text-gray-500">{{ $b->published_at->format('F j, Y') }}</time>
                                        <a href="{{ route('index.berita', ['kategori' => $b->kategori->slug]) }}"
                                            class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ str($b->kategori->name)->ucFirst() }}</a>
                                    </div>
                                    <h3
                                        class="mt-3 mb-2 text-2xl font-semibold leading-8 text-gray-900 group-hover:text-gray-600">
                                        <a href="{{ route('index.berita.show', $b->slug) }}">
                                            <span class="absolute inset-0"></span>
                                            {{ $b->title }}
                                        </a>
                                    </h3>
                                    <div class="prose-lg text-gray-900 group-hover:text-gray-600">
                                        <x-markdown class="text-justify break-words line-clamp-4 ">
                                            {{ $b->body }}
                                        </x-markdown>
                                    </div>
                                    <a rel="noopener noreferrer" href="{{ route('index.berita.show', $b->slug) }}"
                                        class="inline-flex items-center pt-2 pb-6 space-x-2 text-sm dark:text-violet-400">
                                        <span>Read more</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <div class="w-full text-gray-600 gap-10 ">
                                        <div
                                            class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4">
                                            <div class="flex flex-row items-center ml-4 mb-2 lg:mb-0 gap-4">
                                                <img src="{{ $b->author->getFilamentAvatarUrl() }}" alt=""
                                                    class="h-10 w-10 rounded-full object-contain bg-gray-50">
                                                <div class="text-sm leading-6">
                                                    <p class="font-semibold text-gray-900">
                                                        <a href="#">
                                                            <span class="absolute inset-0"></span>
                                                            {{ $b->author->name }}
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="right-0">
                                                <p class="text-xs ">Perkiraan Waktu Membaca
                                                    {{ $b->getReadingTime() }}</p>
                                            </div>
                                        </div>

                                        @foreach ($b->tags as $tag)
                                            @php
                                                $colors = [
                                                    'bg-primary-300',
                                                    'bg-secondary-300',
                                                    'bg-info-300',
                                                    'bg-warning-300',
                                                    'bg-success-300',
                                                    'bg-danger-300',
                                                    'bg-gray-300',
                                                ];
                                                $colorClass = $colors[array_rand($colors)];
                                            @endphp
                                            <span
                                                class="inline-block {{ $colorClass }} text-blue-800 font-semibold px-2 py-1 rounded-lg mr-2 mb-2">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                </div>
                                <div class="hidden lg:w-2/5 lg:flex">
                                    <img src="{{ $b->getThumbnail() }}" alt="{{ $b->slug }}"
                                        class="w-full h-72 object-cover rounded-lg ">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div
                    class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @foreach ($beritas as $b)
                        @if ($loop->first)
                            @continue
                        @endif
                        <div class="flex flex-col ">
                            <div>
                                <img src="{{ $b->getThumbnail() }}" alt="{{ $b->slug }}"
                                    class="w-full h-64 object-cover rounded-lg mb-3">
                            </div>
                            <div>
                                <div class="flex items-center gap-x-4 text-xs">
                                    <time datetime="2020-03-16"
                                        class="text-gray-500">{{ $b->published_at->format('F j, Y') }}</time>
                                    <a href="{{ route('index.berita', ['kategori' => $b->kategori->slug]) }}"
                                        class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ str($b->kategori->name)->ucFirst() }}</a>
                                </div>
                            </div>
                            <div>
                                <div class="group relative min-h-52">
                                    <h3
                                        class="mt-3 mb-2 text-2xl font-semibold leading-8 text-gray-900 group-hover:text-gray-600">
                                        <a href="{{ route('index.berita.show', $b->slug) }}">
                                            <span class="absolute inset-0"></span>
                                            {{ $b->title }}
                                        </a>
                                    </h3>

                                    <div class="prose prose-img:hidden">
                                        <x-markdown class="text-justify break-words line-clamp-4 ">
                                            {{ $b->body }}
                                        </x-markdown>
                                    </div>

                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-gray-600 space-y-3">
                                    <div class="relative mt-3 flex items-center gap-x-4">
                                        <img src="{{ $b->author->getFilamentAvatarUrl() }}" alt=""
                                            class="h-10 w-10 rounded-full object-contain bg-gray-50">
                                        <div class="text-sm leading-6">
                                            <p class="font-semibold text-gray-900">
                                                <a href="#">
                                                    <span class="absolute inset-0"></span>
                                                    {{ $b->author->name }}
                                                </a>
                                            </p>

                                        </div>
                                    </div>

                                    <div class="right-0">
                                        <p class="text-xs ">Perkiraan Waktu Membaca
                                            {{ $b->getReadingTime() }}</p>
                                    </div>

                                    @foreach ($b->tags as $tag)
                                        @php
                                            $colors = [
                                                'bg-primary-300',
                                                'bg-secondary-300',
                                                'bg-info-300',
                                                'bg-warning-300',
                                                'bg-success-300',
                                                'bg-danger-300',
                                                'bg-gray-300',
                                            ];
                                            $colorClass = $colors[array_rand($colors)];
                                        @endphp
                                        <span
                                            class="inline-block {{ $colorClass }} text-blue-800 font-semibold px-2 py-1 rounded-lg mr-2 mb-2">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
