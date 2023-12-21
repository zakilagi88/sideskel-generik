<div class="fixed z-[100] py-3 px-5 w-full bg-white rounded shadow-xl">
    <div class="-mx-1">
        <ul class="flex w-full flex-wrap items-center h-10">
            <li class="block relative" x-data="{ showChildren: false }" @click.away="showChildren=false">
                <a href="#"
                    class="flex items-center h-10 leading-10 px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 bg-indigo-500 text-white"
                    @click.prevent="showChildren=!showChildren">
                    <span class="mr-3 text-xl"> <i class="mdi mdi-gauge"></i> </span>
                    <span>Dashboard</span>
                    <span class="ml-2"> <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute top-auto left-0 min-w-full w-56 z-30 mt-1"
                    x-show="showChildren" style="display: none;"
                    x-transition:enter="transition ease duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4">
                    <span class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -mt-1 ml-6"></span>
                    <div class="bg-white rounded w-full relative z-10 py-1">
                        <ul class="list-reset">
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Dashboard 1</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Dashboard 2</span> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="block relative">
                <a href="#"
                    class="flex items-center h-10 leading-10 px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100">
                    <span class="mr-3 text-xl"> <i class="mdi mdi-widgets-outline"></i> </span>
                    <span>Widgets</span>
                </a>
            </li>
            <li class="block relative" x-data="{ showChildren: false }" @click.away="showChildren=false">
                <a href="#"
                    class="flex items-center h-10 leading-10 px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100"
                    @click.prevent="showChildren=!showChildren">
                    <span class="mr-3 text-xl"> <i class="mdi mdi-layers-outline"></i> </span>
                    <span>UI Elements</span>
                    <span class="ml-2"> <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute top-auto left-0 min-w-full w-56 z-30 mt-1"
                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                    <span class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -mt-1 ml-6"></span>
                    <div class="bg-white rounded w-full relative z-10 py-1">
                        <ul class="list-reset">
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Basic Elements</span>
                                    <span class="ml-2"> <i class="mdi mdi-chevron-right"></i> </span>
                                </a>
                                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute inset-l-full top-0 min-w-full w-56 z-30 mt-1"
                                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                    <span
                                        class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -ml-1 mt-2"></span>
                                    <div class="bg-white rounded w-full relative z-10 py-1">
                                        <ul class="list-reset">
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Accordion</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Buttons</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Badges</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Breadcrumbs</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Dropdown</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Modals</span> </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Advanced Elements</span>
                                    <span class="ml-2"> <i class="mdi mdi-chevron-right"></i> </span>
                                </a>
                                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute inset-l-full top-0 min-w-full w-56 z-30 mt-1"
                                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                    <span
                                        class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -ml-1 mt-2"></span>
                                    <div class="bg-white rounded w-full relative z-10 py-1">
                                        <ul class="list-reset">
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Charts</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Maps</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Drag n Drop</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Slider</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Loader</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Notification</span> </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Forms &amp; Tables</span>
                                    <span class="ml-2"> <i class="mdi mdi-chevron-right"></i> </span>
                                </a>
                                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute inset-l-full top-0 min-w-full w-56 z-30 mt-1"
                                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                    <span
                                        class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -ml-1 mt-2"></span>
                                    <div class="bg-white rounded w-full relative z-10 py-1">
                                        <ul class="list-reset">
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Form Elements</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Advanced Forms</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Basic Tables</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Data Tables</span> </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Icons</span> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="block relative" x-data="{ showChildren: false }" @click.away="showChildren=false">
                <a href="#"
                    class="flex items-center h-10 leading-10 px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100"
                    @click.prevent="showChildren=!showChildren">
                    <span class="mr-3 text-xl"> <i class="mdi mdi-web"></i> </span>
                    <span>Pages</span>
                    <span class="ml-2"> <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute top-auto left-0 min-w-full w-56 z-30 mt-1"
                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                    <span class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -mt-1 ml-6"></span>
                    <div class="bg-white rounded w-full relative z-10 py-1">
                        <ul class="list-reset">
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">User Profile</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Account Settings</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Invoice</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Authentication</span>
                                    <span class="ml-2"> <i class="mdi mdi-chevron-right"></i> </span>
                                </a>
                                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute inset-l-full top-0 min-w-full w-56 z-30 mt-1"
                                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                    <span
                                        class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -ml-1 mt-2"></span>
                                    <div class="bg-white rounded w-full relative z-10 py-1">
                                        <ul class="list-reset">
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Login</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Register</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Reset Password</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">Lock Screen</span> </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Errors</span>
                                    <span class="ml-2"> <i class="mdi mdi-chevron-right"></i> </span>
                                </a>
                                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute inset-l-full top-0 min-w-full w-56 z-30 mt-1"
                                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                    <span
                                        class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -ml-1 mt-2"></span>
                                    <div class="bg-white rounded w-full relative z-10 py-1">
                                        <ul class="list-reset">
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">400</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">404</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">500</span> </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                                    <span class="flex-1">505</span> </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="block relative" x-data="{ showChildren: false }" @click.away="showChildren=false">
                <a href="#"
                    class="flex items-center h-10 leading-10 px-4 rounded cursor-pointer no-underline hover:no-underline transition-colors duration-100 mx-1 hover:bg-gray-100"
                    @click.prevent="showChildren=!showChildren">
                    <span class="mr-3 text-xl"> <i class="mdi mdi-apple-safari"></i> </span>
                    <span>Apps</span>
                    <span class="ml-2"> <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="bg-white shadow-md rounded border border-gray-300 text-sm absolute top-auto left-0 min-w-full w-56 z-30 mt-1"
                    x-show="showChildren" x-transition:enter="transition ease duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                    <span class="absolute top-0 left-0 w-3 h-3 bg-white border transform rotate-45 -mt-1 ml-6"></span>
                    <div class="bg-white rounded w-full relative z-10 py-1">
                        <ul class="list-reset">
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Calender</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Chat</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Email</span> </a>
                            </li>
                            <li class="relative" x-data="{ showChildren: false }" @mouseleave="showChildren=false"
                                @mouseenter="showChildren=true">
                                <a href="#"
                                    class="px-4 py-2 flex w-full items-start hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100 cursor-pointer">
                                    <span class="flex-1">Todo</span> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
