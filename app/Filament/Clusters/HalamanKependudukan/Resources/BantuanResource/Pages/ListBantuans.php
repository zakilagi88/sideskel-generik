<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBantuans extends ListRecords
{
    protected static string $resource = BantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data'),
        ];
    }
}
