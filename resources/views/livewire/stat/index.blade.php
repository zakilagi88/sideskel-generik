<div class="wrapper pt-28 bg-gray-100">

    <!-- Breadcrumbs -->
    <div class="py-4 flex items-start pl-10">
        <x-filament::breadcrumbs :breadcrumbs="[
            '/' => 'Beranda',
        ]" />
    </div>
    <h1 class="flex items-start font-bold text-4xl pl-10">Agama</h1>
    <!-- Dua kolom utama -->
    <div class="flex items-start">
        {{-- <div class="w-1/4 ml-10">
            <h1 class="font-semibold max-w-xl mx-auto pt-8 pb-4">Subjek</h1>
            <div class="bg-transparent max-w-xl mx-auto  " x-data="{ selected: null }">
                <ul class="shadow-box">
                    <!-- Accordion item  -->
                    @foreach ($kategori as $kat)
                        <x-custom.accordion-item :stat="$kat" :key="$kat->id" />
                    @endforeach
                </ul>
            </div>
        </div> --}}
        <!-- Kolom 2: Table dan Chart -->
        <div class="w-3/4 mr-4 mt-10">
            @foreach ($stats as $stat)
                <livewire:stat.stat-display :key="$stat->id" :$stat />
            @endforeach


            <!-- Konten Table -->
            {{-- <div class="m-10">
                {{ $this->form }}
            </div> --}}

            <!-- Konten Chart -->
            {{-- <div class="m-10 space-y-10">
                @foreach ($stats as $stat)
                    <livewire:statistik.stat-display :slug="$k['stat_slug']" :key="$id" :komponen="$k" />
                @endforeach
            </div> --}}

        </div>

    </div>
</div>
