<?php

namespace App\Filament\Clusters\HalamanBerita\Resources\BeritaResource\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeritas extends ListRecords
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
