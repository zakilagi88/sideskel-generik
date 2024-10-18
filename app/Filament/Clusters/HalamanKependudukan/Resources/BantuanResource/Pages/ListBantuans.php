<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBantuans extends ListRecords
{
    protected static string $resource = BantuanResource::class;

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
