<?php

namespace App\Filament\Clusters\Berita\Resources\KategoriBeritaResource\Pages;

use App\Filament\Clusters\Berita\Resources\KategoriBeritaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriBeritas extends ListRecords
{
    protected static string $resource = KategoriBeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
