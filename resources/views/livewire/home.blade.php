<div class="rounded-xl my-10">
    <div class="flex flex-col justify-center">
        <div class="dark:bg-gray-800 dark:text-gray-100 ">
            <div class="grid grid-cols-1 py-20 lg:grid-cols-2 gap-x-10">
                <div
                    class="flex flex-col items-center justify-center p-6 text-center whitespace-normal lg:whitespace-nowrap rounded-sm ">
                    <h1 class="text-3xl font-bold  sm:text-4xl lg:text-5xl mt-10 ">
                        <span class="dark:text-slate-400"> {{ $data['web_title'] }} <br>
                            {{ $data['sebutan_deskel'] }}</span>
                        <span class="dark:text-slate-400">{{ ucwords(strtolower($deskel->dk?->deskel_nama)) }}</span>
                    </h1>
                    <p class="mt-6 mb-8 text-lg sm:mb-12 ">
                        {{ $data['sebutan_kec'] . ' ' . ucwords(strtolower($deskel->kec?->kec_nama)) }},
                        {{ $data['sebutan_kabkota'] . ' ' . ucwords(strtolower(str_replace('KOTA ', '', $deskel->kabkota?->kabkota_nama))) }}
                        <br> {{ $data['sebutan_prov'] . ' ' . ucwords(strtolower($deskel->prov?->prov_nama)) }}
                    </p>
                </div>
                <div class="flex items-center justify-center  ">
                    <img src="{{ asset('storage/' . $data['web_gambar']) }}" alt="SIDeskel" width="288"
                        height="288" class="object-cover w-fit h-72 sm:h-80 lg:h-96 xl:h-112 2xl:h-128">
                </div>
            </div>
            <livewire:components.stat-info />
        </div>
    </div>
    <div class="flex flex-col gap-10 justify-center">
        <div class="flex flex-col lg:flex-row items-center pt-24 gap-10 justify-center" data-aos="fade-in">
            <div class="lg:w-3/12 hidden lg:block py-4">
                <img src="{{ asset('storage/' . $data['kepala_gambar']) }}" width="280" height="280"
                    class="rounded-tl-lg rounded-br-[140px]" data-aos="fade-in">
            </div>
            <div class="max-w-3xl flex flex-col justify-center p-4 md:p-12">
                <!-- Image for mobile view-->
                <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 size-40 mb-10 bg-cover bg-center"
                    style="background-image: url({{ asset('storage/' . $data['kepala_gambar']) }})" width="160"
                    height="160">
                </div>
                <!-- Blockquote -->
                <blockquote class="font-serif">
                    <p><span class="pr-1 font-serif text-2xl text-center text-red-600">"</span>
                        {{ $data['kepala_deskripsi'] }}

                        <span class="pl-1 font-serif text-2xl text-red-600">"</span>
                    </p>
                    <div class="mb-2 mx-auto lg:mx-0 w-3/5 pt-3 border-b-2 border-orange-500 opacity-25"></div>
                    <footer class="m-2">{{ $data['kepala_nama'] }}, <cite
                            class="pl-3 text-red-600 font-bold italic">
                            {{ $data['sebutan_kepala'] . ' ' . $data['sebutan_deskel'] . ' ' . ucwords(strtolower($deskel->dk?->deskel_nama)) }}</cite>
                    </footer>
                    </blockqoute>
            </div>
        </div>
        <div>
            <div class="w-full px-4">
                <div class="text-center mx-auto mb-[60px] lg:mb-20 max-w-[510px]">
                    <span class="font-semibold text-lg text-primary mb-2 block">
                        Agenda
                    </span>
                    <h2 class=" font-bold text-3xl sm:text-4xl md:text-[40px] text-dark mb-4 ">
                        Agenda Kegiatan
                    </h2>
                </div>
            </div>

            <livewire:widgets.jadwal-kegiatan-publik lazy />

            <div class="mx-auto max-w-8xl px-6 lg:px-4">
                <section class="pt-12 lg:pt-24">
                    <div class="">
                        <div class="w-full px-4">
                            <div class="text-center mx-auto mb-[60px] lg:mb-20 max-w-[510px]">
                                <span class="font-semibold text-lg text-primary mb-2 block">
                                    Berita
                                </span>
                                <h2 class=" font-bold text-3xl sm:text-4xl md:text-[40px] text-dark mb-4 ">
                                    {{ $data['berita_judul'] }}
                                </h2>
                                <p class="text-base text-body-color">
                                    {{ $data['berita_deskripsi'] }}
                                </p>
                            </div>
                            <livewire:pages.berita.berita-grid lazy />
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </div>
</div>
