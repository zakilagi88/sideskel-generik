<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTambahans extends ListRecords
{
    protected static string $resource = TambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            Actions\CreateAction::make()->label('Tambah Data'),

        ];
    }
}
