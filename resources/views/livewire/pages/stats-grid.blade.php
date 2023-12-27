<div class="w-4/5">

    @foreach ($stats as $stat)
        <div class="space-y-6" x-show="tab === {{ $stat->id }}">
            <h3 class="text-xl font-bold leading-tight" x-show="{{ $stat->id }}"
                x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
                {{ $stat->heading_tabel }}
            </h3>
            {{-- <p class="text-base text-gray-600" x-show="{{ $stat->id }}"
                x-transition:enter="transition delay-100 duration-500 transform ease-in"
                x-transition:enter-start="opacity-0">
                {{ $stat->deskripsi_tabel }}
            </p> --}}
            <div class="text-base" x-show="{{ $stat->id }}"
                x-transition:enter="transition delay-300 duration-500 transform ease-in"
                x-transition:enter-start="opacity-0">

                @if (isset($stat->path_tabel))
                    @livewire($stat->path_tabel, key($stat->id))
                @endif


            </div>

            <h3 class="text-xl font-bold leading-tight" x-show="{{ $stat->id }}"
                x-transition:enter="transition duration-500 transform ease-in" x-transition:enter-start="opacity-0">
                {{ $stat->heading_grafik }}
            </h3>
            {{-- <p class="text-base text-gray-600" x-show="{{ $stat->id }}"
                x-transition:enter="transition delay-100 duration-500 transform ease-in"
                x-transition:enter-start="opacity-0">
                {{ $stat->deskripsi_grafik }}
            </p> --}}
            <p class="text-xl" x-show="{{ $stat->id }}"
                x-transition:enter="transition delay-200 duration-500 transform ease-in"
                x-transition:enter-start="opacity-0">

                @if (isset($stat->path_grafik))
                    @livewire($stat->path_grafik, key($stat->id))
                @endif

            </p>

        </div>
    @endforeach

</div>
