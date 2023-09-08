<?php

namespace App\Filament\Resources\KartukeluargaResource\Pages;

use App\Filament\Resources\KartukeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartukeluarga extends EditRecord
{
    protected static string $resource = KartukeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
