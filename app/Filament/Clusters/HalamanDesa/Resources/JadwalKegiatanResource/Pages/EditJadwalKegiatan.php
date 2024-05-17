<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalKegiatan extends EditRecord
{
    protected static string $resource = JadwalKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
