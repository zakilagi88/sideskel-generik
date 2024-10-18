<?php

namespace App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriBeritas extends ListRecords
{
    protected static string $resource = KategoriBeritaResource::class;

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
