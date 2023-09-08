
<div>
    
    <x-filament::breadcrumbs :breadcrumbs="[
    
        '/admin/kartukeluargas' => 'Kartu Keluarga',
        '/' => 'List Kartu Keluarga',
    ]" />
    
    
    <div class="flex justify-between mt-3"
        >
    
        <h1 class="font-serif text-3xl">Kartu Keluarga</h1>
        <div>
        {{
            $data
        }}
        </div>
    
    </div>
    <div class="flex flex-col justify-content-start ">
        <form wire:submit="save" class="w-full max-w-md flex mt-2 space-x-4">
            <div class="flex flex-col">
                <label class="block text-gray-700 text-sm font-bold mb-2 " for="fileInput"></label>
                Pilih Berkas
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="fileInput" type="file" wire:model='file'>
            </div>
            <div class="flex items-end py-0 justify-between text-justify ">
                <button class="bg-blue-500 hover:bg-blue-950 text-white text-sm font-semibold px-8 py-[.1rem] rounded focus:outline-none focus:shadow-outline" type="submit">
                Unggah File
                </button>
    
            </div>
    
        </form>
    </div>

</div>

