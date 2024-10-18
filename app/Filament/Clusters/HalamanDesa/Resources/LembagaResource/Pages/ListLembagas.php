<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLembagas extends ListRecords
{
    protected static string $resource = LembagaResource::class;

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
