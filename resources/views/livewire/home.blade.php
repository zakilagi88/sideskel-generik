    {{-- <style>
        #container {
            color: #999;
            text-transform: uppercase;
            font-size: 36px;
            font-weight: bold;
            padding-top: 200px;
            position: fixed;
            width: 100%;
            bottom: 45%;
            display: block;
        }

        #flip {
            height: 50px;
            overflow: hidden;
        }

        #flip>div>div {
            color: #fff;
            padding: 4px 12px;
            height: 45px;
            margin-bottom: 45px;
            display: inline-block;
        }

        #flip div:first-child {
            animation: show 5s linear infinite;
        }

        #flip div div {
            background: #42c58a;
        }

        #flip div:first-child div {
            background: #4ec7f3;
        }

        #flip div:last-child div {
            background: #DC143C;
        }

        @keyframes show {
            0% {
                margin-top: -270px;
            }

            5% {
                margin-top: -180px;
            }

            33% {
                margin-top: -180px;
            }

            38% {
                margin-top: -90px;
            }

            66% {
                margin-top: -90px;
            }

            71% {
                margin-top: 0px;
            }

            99.99% {
                margin-top: 0px;
            }

            100% {
                margin-top: -270px;
            }
        }
    </style> --}}
    <div class="bg-[#96B6C5]">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>

        <div class="flex flex-col justify-center">
            <section class="dark:bg-gray-800 dark:text-gray-100">
                <div
                    class="container flex flex-col justify-center p-6 mx-auto sm:py-12 lg:py-24 lg:flex-row lg:justify-between">
                    <div
                        class="flex flex-col justify-center mt-40 xs:mt-6 lg:mt-6 p-6 text-center rounded-sm lg:max-w-md xl:max-w-lg lg:text-left">
                        <h1 class="text-5xl font-bold inline sm:text-6xl whitespace-nowrap">Sistem Informasi <br>
                            <span class="dark:text-slate-400">Kelurahan Kuripan</span>
                        </h1>
                        <p class="mt-6 mb-8 text-lg sm:mb-12">Kecamatan Banjarmasin Timur, Kota Banjarmasin
                            <br>Provinsi Kalimantan Selatan
                        </p>
                    </div>
                    <div
                        class="flex items-center justify-center p-6 mt-8 lg:mt-0 h-72 sm:h-80 lg:h-96 xl:h-112 2xl:h-128">
                        <img src="{{ asset('images/cek3.png') }}" alt=""
                            class="object-contain h-72 sm:h-80 lg:h-96 xl:h-112 2xl:h-128">
                    </div>
                </div>
            </section>






        </div>


        <section class="pb-20 bg-white rounded-t-3xl" data-aos="fade-up">

            <div class="container flex flex-col mx-auto px-4 transition-all duration-500 ease-in-out">
                <div class="flex mt-6 justify-center items-center flex-col text-center">
                    <h1 class="text-2xl font-bold sm:text-3xl whitespace-nowrap">Statistik
                        <span class="font-normal">Kelurahan Kuripan</span>

                    </h1>
                    <p class="mt-4">
                        Ini adalah informasi mengenai statistik Desa/Kelurahan Anda. Data Sementara
                    </p>
                </div>

                @livewire('widgets.stats-overview')
                <div data-aos="fade-in">
                    @include('livewire.components.tabs-chart')
                </div>

                <div class="flex flex-wrap items-center mt-32" data-aos="fade-right">
                    <div class="w-full md:w-5/12 px-4 mr-auto ml-auto">
                        <div
                            class="text-gray-600 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-gray-100">
                            <i class="fas fa-user-friends text-xl"></i>
                        </div>
                        <h3 class="text-3xl mb-2 font-semibold leading-normal">
                            Working with us is a pleasure
                        </h3>
                        <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-gray-700">
                            Don't let your uses guess by attaching tooltips and popoves to
                            any element. Just make sure you enable them first via
                            JavaScript.
                        </p>
                        <p class="text-lg font-light leading-relaxed mt-0 mb-4 text-gray-700">
                            The kit comes with three pre-built pages to help you get started
                            faster. You can change the text and images and you're good to
                            go. Just make sure you enable them first via JavaScript.
                        </p>
                        <a href="https://www.creative-tim.com/framework/tailwind-starter-kit"
                            class="font-bold text-gray-800 mt-8">Check Tailwind Starter Kit!</a>
                    </div>
                    <div class="w-full md:w-4/12 px-4 mr-auto ml-auto">
                        <div
                            class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg">
                            <img alt="..."
                                src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=1051&amp;q=80"
                                class="w-full align-middle rounded-t-lg" />
                            <blockquote class="relative p-8 mb-4">
                                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95"
                                    class="absolute left-0 w-full block" style="height: 95px; top: -94px;">
                                    <polygon points="-30,95 583,95 583,65" class="text-pink-600 fill-current"></polygon>
                                </svg>
                                <h4 class="text-xl font-bold text-white">
                                    Top Notch Services
                                </h4>
                                <p class="text-md font-light mt-2 text-white">
                                    The Arctic Ocean freezes every winter and much of the
                                    sea-ice then thaws every summer, and that process will
                                    continue whatever happens.
                                </p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container-fluid bg-gray-200">
            <div class="relative pt-16 top-4 pb-32 flex content-center items-center justify-center"
                style="min-height: 95vh">
                <div class="absolute top-0 w-full h-full bg-top bg-cover"
                    style="background-image: url('https://images.unsplash.com/photo-1504556106489-6d450910aeb3?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1067&q=80');">
                    <span id="blackOverlay" class="w-full h-full absolute opacity-50 bg-black"></span>
                </div>
                <div class="container relative mx-auto" data-aos="fade-in">
                    <div class="items-center flex flex-wrap">
                        <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-white">
                            <div>
                                <h4 class="text-3xl text-center mb-1 font-semibold">Nama Desa/Kelurahan</h4>
                                <h5 class="text-2xl text-center mb-1 capitalize">
                                    <span class="text-red-600">
                                        Kota/Kabupaten atau Kecamatan
                                    </span>
                                </h5>
                                <p class="">Informasi mengenai Desa/Kelurahan Anda dapat dilihat di sini.
                                    Informasi
                                    yang
                                    ada
                                    di sini adalah informasi yang telah diinput oleh Admin Kelurahan. Jika ada informasi
                                    yang
                                    kurang tepat, silahkan hubungi Admin Kelurahan Anda.</p>
                                </p>
                                <a href="#"
                                    class="bg-transparent hover:bg-red-500 text-orange-200 font-semibold hover:text-white p-3 border border-red-600 hover:border-transparent rounded inline-block mt-5 cursor-pointer"
                                    data-aos="fade-right">
                                    Selebihnya
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Leader Section -->
            <div class="flex flex-col lg:flex-row items-center pt-24 justify-center" data-aos="fade-in">
                <div class="lg:w-3/12 hidden lg:block py-4">
                    <img src="{{ asset('storage/images/test.jpeg') }}" class="rounded-tl-lg rounded-br-full"
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
            <!-- Wanted Posters Section -->
            <div class="w-full py-5 bg-gray-300">
                <div class="flex flex-col justify-center items-center">
                    <h2 class="text-2xl lg:text-3xl px-2 font-serif text-center font-bold capitalize">
                        Gambar yang menarik untuk memotivasi pengunjung website Anda
                    </h2>
                    <div class="mb-2 mx-auto lg:mx-0 w-4/5 pt-2 border-b-2 border-red-500 opacity-25"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 grid-flow-row gap-3 pt-3">
                    <img src="https://mars.nasa.gov/files/resources/posters/P03-Farmers-Wanted-NASA-Recruitment-Poster-600x.jpg"
                        class="w-full lg:mt-24 shadow-lg" data-aos="flip-left">
                    <img src="https://mars.nasa.gov/files/resources/posters/P04-Surveyors-Wanted-NASA-Recruitment-Poster-600x.jpg"
                        class="w-full lg:mt-10 shadow-lg" data-aos="flip-left">
                    <img src="https://mars.nasa.gov/files/resources/posters/P06-Technicians-Wanted-NASA-Recruitment-Poster-600x.jpg"
                        class="w-full lg:mt-24 shadow-lg" data-aos="flip-right">
                    <img src="https://mars.nasa.gov/files/resources/posters/P01-Explorers-Wanted-NASA-Recruitment-Poster-600x.jpg"
                        class="w-full lg:mt-10 shadow-lg" data-aos="flip-right">
                </div>
            </div>

            <!-- Need You Section -->
            <div class="w-full py-5 bg-gray-200">
                <div class="flex flex-col-reverse lg:flex-row items-center justify-around">
                    <div class="ml-3 text-3xl font-mono" data-aos="fade-right">
                        <p class="pt-3 text-center">
                            Masih belum diisi apa-apa di sini.

                        </p>
                        <div class="flex text-base items-center justify-center mt-5 mb-6">
                            <a href="#"
                                class="bg-red-600 hover:bg-red-700 font-semibold text-white p-3 rounded inline-block mr-5 cursor-pointer">
                                Tekan Saya</a>
                            <a href="#"
                                class="bg-red-600 hover:bg-red-700 font-semibold text-white p-3 rounded inline-block cursor-pointer">Bantuan</a>
                        </div>
                    </div>
                    <div data-aos="flip-left">
                        <img src="https://mars.nasa.gov/files/resources/posters/P08-We-Need-You-NASA-Recruitment-Poster-600x.jpg"
                            class="w-full lg:rounded-lg shadow-xl lg:w-8/12">
                    </div>
                </div>
            </div>

            <!-- Weather/News Segment -->
            <div class="pt-12 flex flex-row flex-wrap md:justify-around bg-gray-200">
                <!-- Weather Segment -->
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
