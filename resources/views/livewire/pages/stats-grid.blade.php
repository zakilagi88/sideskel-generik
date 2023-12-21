<div class="w-3/4">
    <div class="space-y-6" x-show="tab === 1">
        <h3 class="text-xl font-bold leading-tight" x-show="tab === 1"
            x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
            Grafik Penduduk Berdasarkan Pekerjaan
        </h3>
        <p class="text-base text-gray-600" x-show="tab === 1"
            x-transition:enter="transition delay-100 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Jumlah Penduduk Berdasarkan Pekerjaan
        </p>
        <p class="text-xl" x-show="tab === 1" x-transition:enter="transition delay-200 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">

            @foreach ($stats as $stat)
                @if (isset($stat->path_grafik))
                    @livewire($stat->path_grafik, key($stat->id))
                @endif
            @endforeach

        </p>
        <div class="text-base" x-show="tab === 1"
            x-transition:enter="transition delay-300 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            <livewire:widgets.tabel.pekerjaan />

        </div>
    </div>

    <div class="space-y-6" x-show="tab === 2">
        <h3 class="text-xl font-bold leading-tight" x-show="tab === 2"
            x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
            Grafik Penduduk Berdasarkan Kelompok Umur dan Jenis Kelamin
        </h3>
        <p class="text-base text-gray-600" x-show="tab === 2"
            x-transition:enter="transition delay-100 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Jumlah Penduduk Berdasarkan Kelompok Umur dan Jenis Kelamin
        </p>
        <p class="text-xl" x-show="tab === 2" x-transition:enter="transition delay-200 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            @livewire(\App\Livewire\Widgets\Chart\PendudukChart::class)

        </p>
        {{-- <p class="text-base" x-show="tab === 2"
                x-transition:enter="transition delay-300 duration-500 transform ease-in"
                x-transition:enter-start="opacity-0">
                Is this the right batman for me?
            </p> --}}
        <a href="https://twitter.com/smilesharks"
            class="inline-flex items-center justify-center px-8 pt-3 pb-2 mt-4 text-lg text-center text-white no-underline bg-blue-500 border-blue-500 cursor-pointer hover:bg-gray-900 rounded-3xl hover:text-white focus-within:bg-blue-500 focus-within:border-blue-500 focus-within:text-white sm:text-base lg:text-lg"
            class="text-base" x-show="tab === 2"
            x-transition:enter="transition delay-500 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Learn more
        </a>
    </div>

    <div class="space-y-6" x-show="tab === 3">
        <h3 class="text-xl font-bold leading-tight" x-show="tab === 3"
            x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
            BATMAN FOREVER (1995)
        </h3>
        <p class="text-base text-gray-600" x-show="tab === 3"
            x-transition:enter="transition delay-100 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Rottentomatoes 12%
        </p>
        <p class="text-xl" x-show="tab === 3" x-transition:enter="transition delay-200 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Rottentomatoes 38%
        </p>
        <p class="text-base" x-show="tab === 3" x-transition:enter="transition delay-300 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Is this the right batman for me?
        </p>
        <a href="https://twitter.com/smilesharks"
            class="inline-flex items-center justify-center px-8 pt-3 pb-2 mt-4 text-lg text-center text-white no-underline bg-blue-500 border-blue-500 cursor-pointer hover:bg-gray-900 rounded-3xl hover:text-white focus-within:bg-blue-500 focus-within:border-blue-500 focus-within:text-white sm:text-base lg:text-lg"
            class="text-base" x-show="tab === 3"
            x-transition:enter="transition delay-500 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Learn more
        </a>
    </div>

    <div class="space-y-6" x-show="tab === 4">
        <h3 class="text-xl font-bold leading-tight" x-show="tab === 4"
            x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
            BATMAN: THE KILLING JOKE (2016)
        </h3>
        <p class="text-base text-gray-600" x-show="tab === 4"
            x-transition:enter="transition delay-100 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Rottentomatoes 39%
        </p>
        <p class="text-xl" x-show="tab === 4" x-transition:enter="transition delay-200 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Fathom Events, Warner Bros. and DC Comics invite you to a premiere event when Batman: The
            Killing Joke comes to...
        </p>
        <p class="text-base" x-show="tab === 4" x-transition:enter="transition delay-300 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Is this the right batman for me?
        </p>
        <a href="https://twitter.com/smilesharks"
            class="inline-flex items-center justify-center px-8 pt-3 pb-2 mt-4 text-lg text-center text-white no-underline bg-blue-500 border-blue-500 cursor-pointer hover:bg-gray-900 rounded-3xl hover:text-white focus-within:bg-blue-500 focus-within:border-blue-500 focus-within:text-white sm:text-base lg:text-lg"
            class="text-base" x-show="tab === 4"
            x-transition:enter="transition delay-500 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Learn more
        </a>
    </div>

    <div class="space-y-6" x-show="tab === 5">
        <h3 class="text-xl font-bold leading-tight" x-show="tab === 5"
            x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
            JUSTICE LEAGUE (2017)
        </h3>
        <p class="text-base text-gray-600" x-show="tab === 5"
            x-transition:enter="transition delay-100 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Rottentomatoes 40%
        </p>
        <p class="text-xl" x-show="tab === 5" x-transition:enter="transition delay-200 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Fueled by his restored faith in humanity and inspired by Superman's selfless act, Bruce
            Wayne enlists the help of his...
        </p>
        <p class="text-base" x-show="tab === 5"
            x-transition:enter="transition delay-300 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Is this the right batman for me?
        </p>
        <a href="https://twitter.com/smilesharks"
            class="inline-flex items-center justify-center px-8 pt-3 pb-2 mt-4 text-lg text-center text-white no-underline bg-blue-500 border-blue-500 cursor-pointer hover:bg-gray-900 rounded-3xl hover:text-white focus-within:bg-blue-500 focus-within:border-blue-500 focus-within:text-white sm:text-base lg:text-lg"
            class="text-base" x-show="tab === 5"
            x-transition:enter="transition delay-500 duration-500 transform ease-in"
            x-transition:enter-start="opacity-0">
            Learn more
        </a>
    </div>
</div>
