<div class="rounded-xl my-10">
    <div class="flex flex-col justify-center">
        <div class="dark:bg-gray-800 dark:text-gray-100 ">
            <div class="grid grid-cols-1 py-20 lg:grid-cols-2 gap-x-10">
                <div
                    class="flex flex-col items-center justify-center p-6 text-center whitespace-normal lg:whitespace-nowrap rounded-sm ">
                    <h1 class="text-3xl font-bold  sm:text-4xl lg:text-5xl mt-10 ">
                        <span class="dark:text-slate-400"> {{ $data['web_title'] }} <br>
                            {{ $data['sebutan_deskel'] }}</span>
                        <span class="dark:text-slate-400">{{ ucwords(strtolower($deskel->dk->deskel_nama)) }}</span>
                    </h1>
                    <p class="mt-6 mb-8 text-lg sm:mb-12 ">
                        {{ $data['sebutan_kec'] . ' ' . ucwords(strtolower($deskel->kec->kec_nama)) }},
                        {{ $data['sebutan_kabkota'] . ' ' . ucwords(strtolower(str_replace('KOTA ', '', $deskel->kabkota->kabkota_nama))) }}
                        <br> {{ $data['sebutan_prov'] . ' ' . ucwords(strtolower($deskel->prov->prov_nama)) }}
                    </p>
                </div>
                <div class="flex items-center justify-center  ">
                    <img src="{{ asset('storage/' . $data['web_gambar']) }}" alt=""
                        class="object-cover h-72 sm:h-80 lg:h-96 xl:h-112 2xl:h-128">
                </div>
            </div>
            @livewire('components.stat-info')

        </div>
    </div>
    <div class="flex flex-col justify-center">
        <div class="flex flex-col lg:flex-row items-center pt-24 justify-center" data-aos="fade-in">
            <div class="lg:w-3/12 hidden lg:block py-4">
                <img src="{{ asset('storage/' . $data['kepala_gambar']) }}" class="rounded-tl-lg rounded-br-full"
                    data-aos="fade-in">
            </div>
            <div class="max-w-3xl flex flex-col justify-center p-4 md:p-12">
                <!-- Image for mobile view-->
                <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 h-40 w-40 mb-10 bg-cover bg-center"
                    style="background-image: url({{ asset('storage/' . $data['kepala_gambar']) }})">
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
                            {{ $data['sebutan_kepala'] . ' ' . $data['sebutan_deskel'] . ' ' . ucwords(strtolower($deskel->dk->deskel_nama)) }}</cite>
                    </footer>
                    </blockqoute>
            </div>
        </div>
    </div>
    <livewire:widgets.jadwal-kegiatan-publik />
    <livewire:pages.berita.berita-grid :data="$data" />
</div>

</div>
</div>
<script>
    AOS.init({
        offset: 200,
        delay: 100,
        duration: 2000,
    });
</script>
<script>
    const $backTop = $(".back-to-top");
    const isHidden = "is-hidden";

    $(window).on("scroll", function() {
        const $this = $(this);
        if ($this.scrollTop() + $this.height() == $(document).height()) {
            $backTop.removeClass(isHidden);
        } else {
            $backTop.addClass(isHidden);
        }
    });

    $backTop.on("click", () => {
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
        return false;
    });
</script>
