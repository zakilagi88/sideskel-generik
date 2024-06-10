<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource;

use App\Livewire\Widgets\Tables\Dinamika\DinamikaTable;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListDinamikas extends ListRecords
{
    protected static string $resource = DinamikaResource::class;

    public $filterData = [];

    #[On('filterUpdated')]
    public function filterUpdated($filterData): void
    {
        $this->filterData = $filterData;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DinamikaResource\Widgets\FilterDinamika::class,
            DinamikaTable::make(['filterData' => $this->filterData]),
        ];
    }
}
