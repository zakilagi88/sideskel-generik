<?php

namespace App\Filament\Resources\KategoriBeritaResource\Pages;

use App\Filament\Resources\KategoriBeritaResource;
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
