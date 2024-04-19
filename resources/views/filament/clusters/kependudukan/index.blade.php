<div class="wrapper pt-28 bg-gray-100">

    <!-- Breadcrumbs -->
    <div class="py-4 flex items-start pl-10">
        <x-filament::breadcrumbs :breadcrumbs="[
            '/' => 'Beranda',
        ]" />
    </div>
    <h1 class="flex items-start font-bold text-4xl pl-10">{{ $stat->nama }}</h1>
    <!-- Dua kolom utama -->
    <div class="flex items-start">
        <div class="w-1/4 ml-10">
            <h1 class="font-semibold max-w-xl mx-auto pt-8 pb-4">Subjek</h1>
            <div class="bg-transparent max-w-xl mx-auto  " x-data="{ selected: null }">
                <ul class="shadow-box ">
                    <!-- Accordion item  -->
                    @foreach ($kategori as $kat)
                        <x-custom.accordion-item :stat="$kat" :key="$kat->id" />
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Kolom 2: Table dan Chart -->
        <div class="w-3/4 mr-4">
            <div x-data="{ activeTab: 'tab1' }" class="pt-8">
                <div class="flex justify-start ml-10 relative  ">
                    <div class="absolute inset-x-0 bottom-0 border-b-[1px] border-gray-400 "></div>
                    <div class="text-gray-400 flex items-center cursor-pointer px-6 pb-2 z-10 "
                        x-on:click="activeTab = 'tab1'"
                        :class="{ ' border-b-2 border-b-info-400': activeTab === 'tab1' }">
                        <x-filament::icon icon="fas-table" x-bind:class="{ 'text-info-400': activeTab === 'tab1' }"
                            class="h-5 w-5 text-gray-400 dark:text-gray-400" />
                        <span :class="{ 'font-semibold text-info-400': activeTab === 'tab1' }"
                            class="max-w-xl ml-2">Tabel</span>
                    </div>
                    <div class="text-gray-400 flex items-center cursor-pointer px-6 pb-2 z-10 "
                        x-on:click="activeTab = 'tab2'"
                        :class="{ 'relative border-b-2 border-b-info-400': activeTab === 'tab2' }">
                        <x-filament::icon icon="fas-chart-simple"
                            x-bind:class="{ 'text-info-300': activeTab === 'tab2' }"
                            class="h-5 w-5 text-gray-400 dark:text-gray-400" />
                        <span :class="{ 'font-semibold text-info-400': activeTab === 'tab2' }"
                            class="max-w-xl ml-2">Grafik</span>
                    </div>

                </div>



                <div class="ml-10 space-y-2 mt-4">
                    @livewire('stat.filter-form', ['stat' => $stat, 'activeTab' => 'activeTab'])
                </div>
            </div>

        </div>

    </div>
</div>