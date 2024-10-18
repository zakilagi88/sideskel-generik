<?php

namespace App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource\Pages;

use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPotensiSDAS extends ListRecords
{
    protected static string $resource = PotensiSDAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            // Actions\CreateAction::make(),
        ];
    }
}
