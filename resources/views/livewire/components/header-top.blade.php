<header class="flex flex-row justify-start items-center gap-4 p-3 max-w-screen-2xl md:mx-auto">
    <div class="flex items-center space-x-2">
        <x-filament::icon icon="fas-envelope" class="h-5 w-5 text-primary-400 dark:text-gray-400" />
        <p class="text-primary-400 text-sm">{{ $this->deskel->email }}</p>
    </div>
    <div class="flex items-center space-x-2">
        <x-filament::icon icon="fas-phone" class="h-5 w-5 text-primary-400 dark:text-gray-400" />
        <p class="text-primary-400 text-sm ">{{ $this->deskel->telepon }}</p>
    </div>
</header>
