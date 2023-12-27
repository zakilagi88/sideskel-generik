<div class="flex items-center justify-between mt-4">
    <div class="flex items-center">
        <div class="text-sm text-gray-500 ml-2">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </div>
    </div>

    <div class="flex items-center ml-auto">
        @if ($paginator->onFirstPage())
            <x-filament::icon-button disabled icon="fas-chevron-left" label="Sebelumnya" />
        @else
            <x-filament::icon-button icon="fas-chevron-left" wire:click="previousPage" wire:loading.attr="disabled"
                label="Sebelumnya" />
        @endif

        @if ($paginator->hasMorePages())
            <x-filament::icon-button icon="fas-chevron-right" wire:click="nextPage" wire:loading.attr="disabled"
                label="Selanjutnya" />
        @else
            <x-filament::icon-button disabled icon="fas-chevron-right" label="Selanjutnya" />
        @endif
    </div>
</div>
