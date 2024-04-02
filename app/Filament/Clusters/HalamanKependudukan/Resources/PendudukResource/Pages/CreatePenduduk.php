<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenduduk extends CreateRecord
{
    protected static string $resource = PendudukResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nama_lengkap'] = strtoupper($data['nama_lengkap']);
        $data['alamat'] = strtoupper($data['alamat']);
        $data['tempat_lahir'] = strtoupper($data['tempat_lahir']);

        return $data;
    }
}
