<div class="my-4 rounded-xl bg-primary">

    <div class="flex flex-col justify-center">
        <div class="dark:bg-gray-800 dark:text-gray-100">
            <div class="flex flex-col justify-center p-6 mx-auto sm:pt-12 lg:pt-32 lg:flex-row lg:justify-around">
                <div
                    class="flex flex-col lg:max-w-md xl:max-w-lg lg:text-left mt-40 xs:mt-6 lg:mt-6 p-6 text-center rounded-sm">
                    <h1 class="text-5xl font-bold inline sm:text-6xl whitespace-nowrap">Website Resmi <br>
                        <span class="dark:text-slate-400">{{ $deskel->sebutan }}</span>
                        <span class="dark:text-slate-400">{{ ucwords(strtolower($deskel->dk->deskel_nama)) }}</span>
                    </h1>
                    <p class="mt-6 mb-8 text-lg sm:mb-12">Kecamatan {{ ucwords(strtolower($kec)) }},
                        {{ ucwords(strtolower($kabkota)) }}
                        <br>Provinsi {{ ucwords(strtolower($prov)) }}
                    </p>
                </div>
                <div class="flex items-center justify-center mt-8 lg:mt-0 p-6 h-72 sm:h-80 lg:h-96 xl:h-112 2xl:h-128">
                    <img src="{{ asset('storage/images/bg-kantor.png') }}" alt=""
                        class="object-contain h-72 sm:h-80 lg:h-96 xl:h-112 2xl:h-128">
                </div>
            </div>
            @livewire('components.stat-info')
        </div>


        <div class="flex flex-col lg:flex-row items-center pt-24 justify-center" data-aos="fade-in">
            <div class="lg:w-3/12 hidden lg:block py-4">
                <img src="{{ asset('storage/images/contoh.png') }}" class="rounded-tl-lg rounded-br-full"
                    data-aos="fade-in">
            </div>
            <div class="max-w-3xl flex flex-col justify-center p-4 md:p-12">
                <!-- Image for mobile view-->
                <div class="block lg:hidden rounded-full shadow-xl mx-auto -mt-16 h-32 w-32 bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1000&q=80')">
                </div>
                <!-- Blockquote -->
                <blockquote class="font-serif">
                    <p><span class="pr-1 font-serif text-2xl text-center text-red-600">"</span>
                        Isikan dengan kalimat yang menarik untuk memotivasi pengunjung website Anda. Kalimat ini
                        akan menjadi kalimat yang pertama kali dilihat oleh pengunjung website Anda. Jadi, pastikan
                        kalimat ini dapat memotivasi pengunjung website Anda untuk melihat informasi yang ada di
                        website Anda.

                        <span class="font-bold text-red-600">Penekanan</span>.
                        Tambahan kalimat untuk memotivasi pengunjung website Anda
                        <span class="pl-1 font-serif text-2xl text-red-600">"</span>
                    </p>
                    <div class="mb-2 mx-auto lg:mx-0 w-3/5 pt-3 border-b-2 border-orange-500 opacity-25"></div>
                    <footer class="m-2">Zaki Lagi, <cite class="pl-3 text-red-600 font-bold italic">
                            Leader of What</cite></footer>
                    </blockqoute>
                    <!-- Social Media -->
                    <div class="mt-10 max-w-sm lg:pb-0 mx-auto flex justify-around text-3xl">
                        <a class="link" href="#"><i
                                class="fab fa-facebook-square p-2 transition duration-75 ease-in-out hover:text-blue-800 cursor-pointer"></i>
                            <a class="link" href="#"><i
                                    class="fab fa-pinterest-square p-2 transition duration-75 ease-in-out hover:text-red-800 cursor-pointer"></i>
                                <a class="link" href="#"><i
                                        class="fab fa-instagram-square p-2 transition duration-75 ease-in-out hover:text-red-600 cursor-pointer"></i>
                                    <a class="link" href="#"><i
                                            class="fab fa-twitter-square p-2 transition duration-75 ease-in-out hover:text-blue-400 cursor-pointer"></i>
                    </div>
            </div>
        </div>
    </div>
    <div class="pt-12 flex flex-row flex-wrap md:justify-around">
        @livewire('berita.grid')
    </div>
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
