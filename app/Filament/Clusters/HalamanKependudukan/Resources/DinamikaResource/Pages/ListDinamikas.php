<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource;

use App\Livewire\Widgets\Tables\Dinamika\DinamikaTable;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDinamikas extends ListRecords
{
    protected static string $resource = DinamikaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DinamikaTable::class,
        ];
    }
}
