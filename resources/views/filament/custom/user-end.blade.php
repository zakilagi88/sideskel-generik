@php
    $user = filament()
        ->auth()
        ->user();
@endphp

<div x-data="{
    toggle: function(event) {
        $refs.panel.toggle(event)
    },

    open: function(event) {
        $refs.panel.open(event)
    },

    close: function(event) {
        $refs.panel.close(event)
    },
}" class="fi-dropdown fi-user-menu">
    <div x-on:click="toggle" class="fi-dropdown-trigger flex cursor-pointer" aria-expanded="true">
        <button aria-label="Menu pengguna" type="button">
            <div style="background-image: url('https://www.gravatar.com/avatar/114a5440b4b8e6f38903efcd7e7ecba7?s=400&amp;d=robohash&amp;r=pg');"
                class="fi-avatar bg-cover bg-center h-9 w-9 fi-user-avatar rounded-full"></div>
        </button>
    </div>

    <div x-float.placement.bottom-end.flip.teleport.offset="{ offset: 8 }" x-ref="panel"
        x-transition:enter-start="opacity-0" x-transition:leave-end="opacity-0"
        class="fi-dropdown-panel absolute z-10 w-screen divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10 max-w-[14rem]"
        style="position: fixed; display: block; left: 1154.95px; top: 58px;">
        <div class="fi-dropdown-list p-1">
            <a href="http://127.0.0.1:8000/admin/profile" wire:navigate="" style=";"
                class="fi-dropdown-list-item flex w-full items-center gap-2 whitespace-nowrap rounded-md p-2 text-sm transition-colors duration-75 outline-none disabled:pointer-events-none disabled:opacity-70 fi-color-gray fi-dropdown-list-item-color-gray hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5">
                <!-- __BLOCK__ --> <svg class="fi-dropdown-list-item-icon h-5 w-5 text-gray-400 dark:text-gray-500"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                        clip-rule="evenodd"></path>
                </svg> <!-- __ENDBLOCK__ -->


                <span class="fi-dropdown-list-item-label flex-1 truncate text-start text-gray-700 dark:text-gray-200">
                    Profil
                </span>

            </a>
        </div>



        <div class="fi-dropdown-list p-1">
            <div x-data="{
                theme: null,
            
                init: function() {
                    this.theme = localStorage.getItem('theme') || 'system'
            
                    $dispatch('theme-changed', theme)
            
                    $watch('theme', (theme) => {
                        $dispatch('theme-changed', theme)
                    })
                },
            }" class="fi-theme-switcher grid grid-flow-col gap-x-1">
                <button aria-label="Mode Terang" type="button"
                    x-bind:class="theme === 'light' ? 'bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400' :
                        'text-gray-400 hover:text-gray-500 focus:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus:text-gray-400'"
                    x-on:click="(theme = 'light') &amp;&amp; close()"
                    x-tooltip="{
        content: 'Mode Terang',
        theme: $store.theme,
    }"
                    class="flex justify-center rounded-lg p-2 outline-none transition duration-75 hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5 bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400">
                    <!-- __BLOCK__ --> <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path
                            d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 001.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 15.657a.75.75 0 001.06-1.06l-1.06-1.061a.75.75 0 10-1.06 1.06l1.06 1.06zM5.404 6.464a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.061 1.06l1.06 1.06z">
                        </path>
                    </svg> <!-- __ENDBLOCK__ -->
                </button>

                <button aria-label="Mode Gelap" type="button"
                    x-bind:class="theme === 'dark' ? 'bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400' :
                        'text-gray-400 hover:text-gray-500 focus:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus:text-gray-400'"
                    x-on:click="(theme = 'dark') &amp;&amp; close()"
                    x-tooltip="{
        content: 'Mode Gelap',
        theme: $store.theme,
    }"
                    class="flex justify-center rounded-lg p-2 outline-none transition duration-75 hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5 text-gray-400 hover:text-gray-500 focus:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus:text-gray-400">
                    <!-- __BLOCK__ --> <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M7.455 2.004a.75.75 0 01.26.77 7 7 0 009.958 7.967.75.75 0 011.067.853A8.5 8.5 0 116.647 1.921a.75.75 0 01.808.083z"
                            clip-rule="evenodd"></path>
                    </svg> <!-- __ENDBLOCK__ -->
                </button>

                <button aria-label="Sesuai tema perangkat" type="button"
                    x-bind:class="theme === 'system' ? 'bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400' :
                        'text-gray-400 hover:text-gray-500 focus:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus:text-gray-400'"
                    x-on:click="(theme = 'system') &amp;&amp; close()"
                    x-tooltip="{
        content: 'Sesuai tema perangkat',
        theme: $store.theme,
    }"
                    class="flex justify-center rounded-lg p-2 outline-none transition duration-75 hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5 text-gray-400 hover:text-gray-500 focus:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus:text-gray-400">
                    <!-- __BLOCK__ --> <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M2 4.25A2.25 2.25 0 014.25 2h11.5A2.25 2.25 0 0118 4.25v8.5A2.25 2.25 0 0115.75 15h-3.105a3.501 3.501 0 001.1 1.677A.75.75 0 0113.26 18H6.74a.75.75 0 01-.484-1.323A3.501 3.501 0 007.355 15H4.25A2.25 2.25 0 012 12.75v-8.5zm1.5 0a.75.75 0 01.75-.75h11.5a.75.75 0 01.75.75v7.5a.75.75 0 01-.75.75H4.25a.75.75 0 01-.75-.75v-7.5z"
                            clip-rule="evenodd"></path>
                    </svg> <!-- __ENDBLOCK__ -->
                </button>
            </div>
        </div>

        <div class="fi-dropdown-list p-1">
            <form action="http://127.0.0.1:8000/admin/logout" method="post">
                <input type="hidden" name="_token" value="ykq7PBbyBmw0NIdIyO0m69BKQibe4AJui1AkSzDO"
                    autocomplete="off">
                <button type="submit" style=";"
                    class="fi-dropdown-list-item flex w-full items-center gap-2 whitespace-nowrap rounded-md p-2 text-sm transition-colors duration-75 outline-none disabled:pointer-events-none disabled:opacity-70 fi-color-gray fi-dropdown-list-item-color-gray hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5">
                    <!-- __BLOCK__ --> <svg class="fi-dropdown-list-item-icon h-5 w-5 text-gray-400 dark:text-gray-500"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z"
                            clip-rule="evenodd"></path>
                        <path fill-rule="evenodd"
                            d="M19 10a.75.75 0 00-.75-.75H8.704l1.048-.943a.75.75 0 10-1.004-1.114l-2.5 2.25a.75.75 0 000 1.114l2.5 2.25a.75.75 0 101.004-1.114l-1.048-.943h9.546A.75.75 0 0019 10z"
                            clip-rule="evenodd"></path>
                    </svg> <!-- __ENDBLOCK__ -->

                    <span
                        class="fi-dropdown-list-item-label flex-1 truncate text-start text-gray-700 dark:text-gray-200">
                        Keluar
                    </span>

                </button>
            </form>
        </div>
    </div>
</div>
