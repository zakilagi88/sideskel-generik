<?php

namespace App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource\Pages;

use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatSDMs extends ListRecords
{
    protected static string $resource = StatSDMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}