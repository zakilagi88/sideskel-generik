<?php

namespace App\Filament\Resources\KartukeluargaResource\Pages;

use App\Filament\Resources\KartukeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKartukeluarga extends CreateRecord
{
    protected static string $resource = KartukeluargaResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
