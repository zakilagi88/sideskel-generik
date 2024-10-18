<?php

namespace App\Filament\Clusters\HalamanArsip\Resources\PeraturanResource\Pages;

use App\Filament\Clusters\HalamanArsip\Resources\PeraturanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeraturans extends ListRecords
{
    protected static string $resource = PeraturanResource::class;

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
