<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\AparaturResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Clusters\HalamanDesa\Resources\JabatanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAparaturs extends ListRecords
{
    protected static string $resource = AparaturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            Actions\CreateAction::make()->label('Tambah Data'),
            Actions\Action::make('jabatan')
                ->label('Data Jabatan')
                ->icon('fas-cogs')
                ->url(JabatanResource::getUrl('index'))
        ];
    }
}
