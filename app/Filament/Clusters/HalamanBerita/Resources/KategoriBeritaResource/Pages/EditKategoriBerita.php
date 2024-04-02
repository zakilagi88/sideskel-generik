<?php

namespace App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriBerita extends EditRecord
{
    protected static string $resource = KategoriBeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
