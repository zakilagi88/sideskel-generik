<?php

namespace App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource\Pages;

use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;

class ListStatSDMs extends ListRecords
{
    protected static string $resource = StatSDMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data Statistik Kependudukan')
                ->modalAlignment(Alignment::Center)
                ->modalWidth(MaxWidth::FourExtraLarge),
        ];
    }
}
