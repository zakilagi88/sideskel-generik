    @props(['data'])

    <nav id="navbar"
        class="sticky shadow-md z-[24] mb-6 top-0 p-3 left-0  tracking-wide transition duration-200 ease-in-out right-0 bg-primary-400"
        x-data="{ mobileMenuOpen: false }">
        <div class="flex items-center justify-evenly ">
            <div class="flex items-center">
                <img src="{{ asset('storage/images/bjm.png') }}" alt="SIDeskel"
                    class="size-12 lg:size-20 bg-transparent object-contain">
                <div class="flex flex-col ml-4 text-pretty">
                    <a id="nama" href="#"
                        class="text-xs lg:text-lg font-bold text-white dark:text-gray-400 tracking-wide whitespace-nowrap">
                        Kelurahan
                        Kuripan</a>
                    <a id="nama2" href="#"
                        class="text-xs lg:text-lg font-normal text-white dark:text-gray-400 tracking-wide whitespace-nowrap">Kecamatan
                        Banjarmasin Timur</a>
                </div>
            </div>

            <ul class="hidden md:flex space-x-1">
                @foreach ($data as $m)
                    @if (!empty($m['submenu']))
                        <li
                            class="flex relative group hover:bg-white mt-2 px-4 pb-2 text-lg text-white hover:text-primary-400 rounded-t-lg ">
                            <x-web.nav-link :items="$m" />

                            <x-filament::icon icon="fas-chevron-down" class="size-3 mt-2 ml-1 " />
                            <!-- Submenu starts -->
                            <ul
                                class="absolute bg-white text-lg rounded-tr-lg rounded-b-lg  border-l-[6px] border-l-primary-400  p-3 -ml-4 w-56 top-9 transform scale-0 group-hover:scale-100 transition duration-150 hover:ease-in-out origin-top shadow-lg">
                                @foreach ($m['submenu'] as $sm)
                                    <li
                                        class="text-md hover:bg-secondary-100 leading-8 hover:px-4 rounded-lg transition duration-200 ease-in-out origin-top">
                                        <x-web.nav-link :items="$sm" />
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <!-- Submenu ends -->
                        <li class=" hover:bg-white text-lg text-white hover:text-primary-400 rounded-lg my-2 px-4">
                            <x-web.nav-link :items="$m" />
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="relative  w-full md:w-auto flex justify-end items-center mr-4">
                <div class="block text-gray-600 cursor-pointer md:hidden">
                    <x-filament::icon-button x-show="!mobileMenuOpen" @click="mobileMenuOpen = true" icon="fas-bars"
                        label="New label" size="xs" class="text-white hover:text-white" />
                    <x-filament::icon-button x-show="mobileMenuOpen" @click="mobileMenuOpen = false" icon="fas-xmark"
                        label="New label" size="xs"
                        class="transition-full each-in-out transform duration-500 text-white hover:text-white" />
                </div>
            </div>
        </div>

        <div x-ref="mobileMenu" x-data="submenu" :style="calculateMaxHeight()"
            class="relative w-full overflow-hidden transition-all duration-700 lg:hidden  max-h-0">
            <div class="flex flex-col my-3 space-y-2">
                <ul class="leading-8 text-md font-semibold">
                    @foreach ($data as $index => $m)
                        <x-web.nav-accordion :items="$m" :key="$index" />
                    @endforeach
                </ul>

            </div>

        </div>

    </nav>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('submenu', () => ({
                openMenu: false,
                selected: null,

                calculateMaxHeight() {
                    if (this.mobileMenuOpen) {
                        return {
                            'max-height': this.$refs.mobileMenu.scrollHeight + (this.selected === null ?
                                0 : this.$refs['subMenu' + this.selected].scrollHeight) + 'px',
                        };
                    } else {
                        return {
                            'max-height': '0px',
                        };
                    }
                }
            }))
        })
    </script>

    {{-- <script>
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

        const navbar = document.getElementById('navbar');
        const threshold = 100;

        function toggleNavbarBackground() {
            if (window.scrollY > threshold) {
                // navbar.classList.add('fixed');
                // navbar.classList.add('mx-12');
            } else {
                // navbar.classList.remove('mx-12');
                //navbar.classList.remove('fixed');
                //navbar.classList.remove('sticky');
            }
        }

        window.addEventListener('scroll', toggleNavbarBackground);
    </script> --}}
