<div class="bg-primary-400 sticky top-[100vh]">
    <div class=" pt-16  w-full max-w-screen-2xl md:mx-auto px-20 md:px-24 lg:px-8 ">
        <div class="grid gap-10 row-gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4 ">
            <div class="sm:col-span-2">
                <div class="inline-flex items-center">
                    <img src="{{ $deskel->getLogo() }}" alt="SIDeskel"
                        class="size-12 lg:size-20 bg-transparent object-contain rounded-xl">
                    <div class="flex flex-col ml-4 text-pretty">
                        <a id="nama" href="#"
                            class="text-xs lg:text-lg font-bold text-white dark:text-gray-400 tracking-wide whitespace-nowrap">
                            {{ $data['sebutan_deskel'] . ' ' . ucwords(strtolower($deskel->dk->deskel_nama)) }}</a>
                        <a id="nama2" href="#"
                            class="text-xs lg:text-lg font-normal text-white dark:text-gray-400 tracking-wide whitespace-nowrap">
                            {{ $data['sebutan_kec'] . ' ' . ucwords(strtolower($deskel->kec->kec_nama)) }}</a>
                    </div>
                </div>
                <div class="mt-6 lg:max-w-sm">
                    <p class="mt-4 text-sm text-white text-justify">
                        {{ $data['footer_deskripsi'] }}
                    </p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <p class="text-base font-bold tracking-wide text-white dark:text-gray-400 mb-2">Informasi Kontak Penting
                </p>
                <div class="flex">
                    <p class="mr-1 text-white">Telepon:</p>
                    <a href="tel:{{ $deskel->telepon }}" aria-label="Our phone" title="Our phone"
                        class="transition-colors duration-300  font-semibold text-white hover:text-secondary-100">{{ $deskel->telepon }}</a>
                </div>
                <div class="flex">
                    <p class="mr-1 text-white">Email:</p>
                    <a href="mailto:{{ $deskel->email }}" aria-label="Our email" title="Our email"
                        class="transition-colors duration-300  font-semibold text-white hover:text-secondary-100">{{ $deskel->email }}</a>
                </div>
                <div class="flex">
                    <p class="mr-1 text-white">Alamat:</p>
                    <a href="https://www.google.com/maps" target="_blank" rel="noopener noreferrer"
                        aria-label="Our address" title="Our address"
                        class="transition-colors duration-300 font-semibold text-white hover:text-secondary-100">
                        {{ $deskel->alamat }}
                    </a>
                </div>
            </div>
            <div>
                <span class="text-base font-bold tracking-wide text-white dark:text-gray-400 mb-2">Social Media</span>
                <div class="flex items-center mt-1 space-x-3">
                    <x-filament::icon icon="fab-facebook" class="h-5 w-5 text-white dark:text-gray-400" />
                    <x-filament::icon icon="fab-instagram" class="h-5 w-5 text-white dark:text-gray-400" />
                    <x-filament::icon icon="fab-youtube" class="h-5 w-5 text-white dark:text-gray-400" />
                </div>
                <p class="mt-4 text-sm text-white dark:text-gray-400">
                    Jangan lupa untuk follow kami di media sosial kami.
                </p>
            </div>
        </div>
        <div class="flex flex-col-reverse justify-between pt-5 pb-10 border-t lg:flex-row">
            <p class="text-sm text-secondary-100">
                2024 © SIDeskel Generik.
            </p>
        </div>
    </div>
</div>
