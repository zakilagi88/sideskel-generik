<div>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/kartukeluarga' => 'Kartu Keluarga',
        '/admin' => 'List Kartu Keluarga',
    ]" />

    <div class="flex justify-between mt-3">
        <h1 class="font-semibold text-3xl">Kartu Keluarga</h1>
        <div>

            {{ $data }}

            {{ $uploadFile }}
 
            <x-filament-actions::modals />

            <div class="flex flex-row items-center ">
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
