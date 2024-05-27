<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTambahans extends ListRecords
{
    protected static string $resource = TambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data'),

        ];
    }
}