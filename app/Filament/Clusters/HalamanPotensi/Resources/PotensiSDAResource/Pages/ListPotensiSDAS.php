<?php

namespace App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource\Pages;

use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPotensiSDAS extends ListRecords
{
    protected static string $resource = PotensiSDAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
