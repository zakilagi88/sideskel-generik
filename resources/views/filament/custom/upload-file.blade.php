<div>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/kartukeluarga' => 'Kartu Keluarga',
        '/' => 'List Kartu Keluarga',
    ]" />

    <div class="flex justify-between mt-3">
        <h1 class="font-semibold text-3xl">Kartu Keluarga</h1>
        <div>
            {{ $data }}
            {{ $uploadFile }}
            <x-filament-actions::modals />
            <div class="flex flex-row items-center mt-4 ">
                @if ($isImporting && !$importFinished)
                    <div class="flex flex-col w-full max-w-lg mb-4">
                        <span wire:poll="updateImportProgress"
                            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-lg text-teal-600 bg-teal-200">
                            Silahkan tunggu ...{{ ' ' . $importProgress . '%' }}
                        </span>
                    </div>
                @endif

            </div>
        </div>

    </div>




</div>


{{-- <div class="flex flex-row items-center mt-4 ">
        <form wire:submit.prevent="save" enctype="multipart/form-data" class="w-full max-w-md flex mt-2 space-x-4">
            @csrf
            <div x-data="{ uploading: false, progress: 0, uploaded: false }" x-on:livewire-upload-start="uploading = true; uploaded = false;"
                x-on:livewire-upload-finish="uploading = false; uploaded = true;"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div class="flex flex-col w-full max-w-lg mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fileInput">Pilih Berkas</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="fileInput" type="file" wire:model="file" x-on:input="fileSelected">
                </div>


                <div class="flex flex-col items-stretch space-y-2 justify-end gap-y-1">
                    <x-filament::button x-ref="fileInput"
                        class="bg-blue-500 whitespace-nowrap hover:bg-blue-950 text-white text-sm font-semibold px-10 py-[.2rem] rounded focus:outline-none focus:shadow-outline"
                        type="button" wire:click="downloadTemplate">
                        Unduh Template
                    </x-filament::button>

                    <x-filament::button type="submit"
                        class="bg-blue-500 whitespace-nowrap hover:bg-blue-950 text-white text-sm font-semibold px-10 py-[.2rem] rounded focus:outline-none focus:shadow-outline"
                        wire:loading.attr="disabled" wire:target="save">
                        Import
                        @if ($isImporting && !$importFinished)
                            <x-filament::loading-indicator class="h-5 w-5" />
                            <span class="ml-2">Mengimpor...</span>
                        @endif
                    </x-filament::button>

                    <x-filament::button type="button" x-on:click="$refs.fileInput.value = null"
                        wire:click="uploaded ? handleCancel : fileSelected = true" x-show="fileSelected && !uploading"
                        class="bg-red-500 whitespace-nowrap hover:bg-red-700 text-white text-sm font-semibold px-10 py-[.2rem] rounded focus:outline-none focus:shadow-outline">
                        Batal

                    </x-filament::button>

                    <span x-show="fileSelected" class="text-green-500" x-text="fileSelected">true</span>

                </div>
                <div x-show="uploading" class="mt-2">
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span x-text="progress >= 100 ? 'Selesai ' : 'Sedang mengunggah...'"
                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                                </span>
                            </div>
                            <div>
                                <div x-bind:style="'width:' + progress + '%'" style="width: 0%">
                                    <span x-text="progress >= 100 ? '100%' : progress + '%'"
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="uploaded" class="mt-2">
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span x-text="progress >= 100 ? 'Selesai ' : 'Sedang mengunggah...'"
                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                                </span>
                            </div>
                            <div>
                                <div x-bind:style="'width:' + progress + '%'" style="width: 0%">
                                    <span x-text="progress >= 100 ? '100%' : progress + '%'"
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


        </form>
    </div> --}}
