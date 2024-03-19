<?php

namespace App\Filament\Clusters\Kependudukan\Resources\BantuanResource\Pages;

use App\Filament\Clusters\Kependudukan\Resources\BantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBantuans extends ListRecords
{
    protected static string $resource = BantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
