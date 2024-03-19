<?php

namespace App\Filament\Clusters\Wilayah\Resources\WilayahResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\WilayahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
