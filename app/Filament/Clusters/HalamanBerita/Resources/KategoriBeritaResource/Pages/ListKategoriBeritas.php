<?php

namespace App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriBeritas extends ListRecords
{
    protected static string $resource = KategoriBeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data'),
        ];
    }
}
