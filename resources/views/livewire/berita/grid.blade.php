<div class="bg-transparent ">
    <div class="mx-auto max-w-8xl px-6 lg:px-4">

        <section class="pt-20 lg:pt-[120px]">
            <div class="container">
                <div class="w-full px-4">
                    <div class="text-center mx-auto mb-[60px] lg:mb-20 max-w-[510px]">
                        <span class="font-semibold text-lg text-primary mb-2 block">
                            Halaman Berita
                        </span>
                        <h2 class=" font-bold text-3xl sm:text-4xl md:text-[40px] text-dark mb-4 ">
                            Berita Terbaru
                        </h2>
                        <p class="text-base text-body-color">
                            Berita ini akan selalu
                            di update setiap ada berita terbaru. Jangan lupa untuk selalu cek berita terbaru dari
                            kelurahan. Terima kasih.
                        </p>
                    </div>
                </div>
                @foreach ($beritas as $berita)
                    @if ($loop->first)
                        <div class="bg-transparent dark:bg-gray-800 dark:text-gray-50">
                            <div class="container grid grid-cols-12 mx-auto dark:bg-gray-900">
                                <div class="bg-no-repeat bg-cover dark:bg-gray-700 col-span-full lg:col-span-4"
                                    style="background-image: url('{{ asset('storage/' . $berita->featured_image_url) }}');
                                    background-position: center center; background-blend-mode: multiply;
                                    background-size: cover;">
                                </div>
                                <div class="flex flex-col p-6 col-span-full row-span-full lg:col-span-8 lg:p-10">
                                    <div class="flex justify-start">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full dark:bg-violet-400 dark:text-gray-900">Label</span>
                                    </div>
                                    <h1 class="text-3xl font-semibold">{{ $berita->title }}</h1>
                                    <p class="flex-1 pt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        Iste,
                                        reprehenderit adipisci tempore voluptas laborum quod.</p>
                                    <a rel="noopener noreferrer" href="#"
                                        class="inline-flex items-center pt-2 pb-6 space-x-2 text-sm dark:text-violet-400">
                                        <span>Read more</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <div class="flex items-center justify-between pt-2">
                                        <div class="flex space-x-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" class="w-5 h-5 dark:text-gray-400">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="self-center text-sm">by Leroy Jenkins</span>
                                        </div>
                                        <span class="text-xs">3 min read</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div
                    class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @foreach ($beritas as $berita)
                        @if ($loop->first)
                            @continue
                        @endif
                        <berita class="flex flex-col ">
                            <div>
                                <img src="{{ asset('storage/' . $berita->featured_image_url) }}"
                                    alt="{{ $berita->slug }}" class="w-full h-64 object-cover rounded-lg mb-3">
                            </div>
                            <div>
                                <div class="flex items-center gap-x-4 text-xs">
                                    <time datetime="2020-03-16"
                                        class="text-gray-500">{{ $berita->published_at->format('F j, Y') }}</time>
                                    <a href="{{ route('kategori_berita', $berita->kategori_berita) }}"
                                        class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ str($berita->category->name)->ucFirst() }}</a>
                                </div>
                            </div>
                            <div>
                                <div class="group relative" style="min-height: 18rem">
                                    <h3
                                        class="mt-3 mb-2 text-2xl font-semibold leading-8 text-gray-900 group-hover:text-gray-600">
                                        <a href="{{ route('berita', $berita->slug) }}">
                                            <span class="absolute inset-0"></span>
                                            {{ $berita->title }}
                                        </a>
                                    </h3>

                                    <div class="prose prose-img:hidden">
                                        <x-markdown class="text-justify break-words line-clamp-4 ">
                                            {{ $berita->body }}
                                        </x-markdown>
                                    </div>

                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-gray-600 space-y-3">
                                    <div class="relative mt-3 flex items-center gap-x-4">
                                        <img src="{{ asset($berita->user->getFilamentAvatarUrl()) }}" alt=""
                                            class="h-10 w-10 rounded-full object-contain bg-gray-50">
                                        <div class="text-sm leading-6">
                                            <p class="font-semibold text-gray-900">
                                                <a href="#">
                                                    <span class="absolute inset-0"></span>
                                                    {{ $berita->user->name }}
                                                </a>
                                            </p>

                                        </div>
                                    </div>

                                    @foreach ($berita->tags as $tag)
                                        @php
                                            $colors = ['bg-blue-100', 'bg-red-100', 'bg-green-100', 'bg-yellow-100', 'bg-indigo-100', 'bg-purple-100', 'bg-pink-100', 'bg-gray-100'];
                                            $colorClass = $colors[array_rand($colors)];
                                        @endphp
                                        <span
                                            class="inline-block {{ $colorClass }} text-blue-800 font-semibold px-2 py-1 rounded-lg mr-2 mb-2">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>

                            </div>
                        </berita>
                    @endforeach
                </div>

        </section>


    </div>
</div>
