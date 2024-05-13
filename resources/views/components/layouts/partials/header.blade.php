<nav id="navbar" class="container-fluid sticky top-0 w-full transition duration-300 ease-in-out bg-primary bg-scroll">

    <div class="flex justify-around items-center gap-4">
        <div class="flex flex-row items-center">
            <img src="{{ asset('storage/images/logo.png') }}" alt="SIDeskel"
                class="h-28 w-28 bg-transparent object-contain">
            <div class="ml-4">
                <h1>
                    <a id=nama href="#" class="text-sm lg:text-xl font-bold text-white">Kelurahan
                        Kuripan</a>
                </h1>
                <h1>
                    <a id=nama2 href="#" class="text-sm lg:text-lg font-normal text-white">Kecamatan
                        Banjarmasin
                        Timur</a>
                </h1>
            </div>
        </div>


        <ul class="hidden md:flex space-x-6">
            <li class=" hover:bg-slate-100 text-white hover:text-black rounded-lg px-2">
                <a href="#">Beranda</a>
            </li>
            <li class=" hover:bg-slate-100 text-white hover:text-black rounded-lg px-2"><a href="#">Berita</a>
            </li>
            <li class="flex relative group hover:bg-slate-100 text-white hover:text-black rounded-lg px-2 ">
                <a href="#" class="mr-1 ">Data
                    Publik</a>
                <i class="fas fa-chevron-down text-xl pt-2"></i>
                <!-- Submenu starts -->
                <ul
                    class="absolute bg-white rounded-lg p-3 ml-0 w-52 top-6 transform scale-0 group-hover:scale-100 transition duration-150 ease-in-out origin-top shadow-lg">
                    <li
                        class="text-sm  hover:bg-slate-100 leading-8 hover:px-4 rounded-lg transition duration-200 ease-in-out origin-top ">
                        <a href="#">Data Penduduk</a>
                    </li>
                    <li
                        class="text-sm  hover:bg-slate-100 leading-8 hover:px-4 rounded-lg transition duration-200 ease-in-out origin-top ">
                        <a href="#">Data Desa</a>
                    </li>
                    <li
                        class="text-sm  hover:bg-slate-100 leading-8 hover:px-4 rounded-lg transition duration-200 ease-in-out origin-top ">
                        <a href="#">Dataku</a>
                    </li>

                </ul>
                <!-- Submenu ends -->
            </li>
            <li class=" hover:bg-slate-100 text-white hover:text-black rounded-lg px-2 "><a href="#">Profil
                    Desa</a></li>
            <li class=" hover:bg-slate-100  text-white hover:text-black rounded-lg px-2 "><a href="#">Tentang</a>
            </li>
        </ul>

        <a href="#" class="bg-red-400 px-5 py-1 rounded-3xl hover:bg-red-500 text-white hidden md:flex"
            role="button">Sign In</a>

        <!-- Mobile menu icon -->
        {{-- <button id="mobile-icon" class="md:hidden">
                    <i onclick="changeIcon(this)" class="fas fa-bars"></i>
                </button> --}}

        <button id="mobile-icon" x-data="{ open: false }"
            class="flex items-center space-x-1 focus:outline-none md:hidden">

            <div class="w-6 flex items-center justify-center relative" x-on:click="open = !open">
                <span x-bind:class="open ? 'translate-y-0 rotate-45' : '-translate-y-2'"
                    class="transform transition w-full p-[1px] rounded-lg h-px bg-current absolute"></span>

                <span x-bind:class="open ? 'opacity-0 translate-x-3' : 'opacity-100'"
                    class="transform transition w-full p-[1px] rounded-lg h-px bg-current absolute"></span>

                <span x-bind:class="open ? 'translate-y-0 -rotate-45' : 'translate-y-2'"
                    class="transform transition w-full p-[1px] rounded-lg h-px bg-current absolute"></span>
            </div>

        </button>

    </div>

    <!-- Mobile menu -->
    <div class="md:hidden flex justify-center w-full">
        <div id="mobile-menu" class="mobile-menu absolute top-20 w-full md:hidden">
            <!-- add hidden here later -->
            <ul class="bg-gray-200 shadow-lg  leading-9 font-bold ">
                <li class="border-b-2 border-white border-t-2 hover:bg-red-400 hover:text-white pl-4"><a
                        href="https://google.com" class="block pl-7">Beranda</a>
                    <!-- Mobile menu icon -->

                </li>
                <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="#"
                        class="block pl-7">Berita</a></li>
                <li class="border-b-2 border-white hover:bg-red-400 hover:text-white">
                    <a href="#" class="block pl-11">Data Publik <i
                            class="fas fa-chevron-down text-2xl  pt-2"></i></a>

                    <!-- Submenu starts -->
                    <ul class="bg-white text-gray-800 w-full">
                        <li class="text-sm leading-8 font-normal hover:bg-slate-200 rounded-lg"><a class="block pl-16"
                                href="#">Data Penduduk</a></li>
                        <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16"
                                href="#">Data Desa</a></li>
                        <li class="text-sm leading-8 font-normal hover:bg-slate-200"><a class="block pl-16"
                                href="#">Dataku</a></li>

                    </ul>
                    <!-- Submenu ends -->
                </li>
                <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="#"
                        class="block pl-7">Profil Desa</a></li>
                <li class="border-b-2 border-white hover:bg-red-400 hover:text-white pl-4"><a href="#"
                        class="block pl-7">Tentang</a></li>
            </ul>
        </div>
    </div>

</nav>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
    const mobile_icon = document.getElementById('mobile-icon');
    const mobile_menu = document.getElementById('mobile-menu');
    const hamburger_icon = document.querySelector("#mobile-icon i");

    function openCloseMenu() {
        mobile_menu.classList.toggle('block');
        mobile_menu.classList.toggle('active');
    }

    function changeIcon(icon) {
        if (icon.classList.contains('fa-bars')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }

    }

    mobile_icon.addEventListener('click', openCloseMenu);
</script>
<script>
    const navbar = document.getElementById('navbar');
    const nama = document.getElementById('nama');
    const nama2 = document.getElementById('nama2');

    const threshold = 100; // Jarak scroll yang diperlukan sebelum mengubah latar belakang

    function toggleNavbarBackground() {
        if (window.scrollY > threshold) {
            navbar.classList.add('bg-white');
            nama.classList.add('text-black');
            nama2.classList.add('text-black');
        } else {
            navbar.classList.remove('bg-white');
            nama.classList.remove('text-black');
            nama2.classList.remove('text-black');
        }
    }

    window.addEventListener('scroll', toggleNavbarBackground);
</script>
