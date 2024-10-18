<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalKegiatans extends ListRecords
{
    protected static string $resource = JadwalKegiatanResource::class;

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
