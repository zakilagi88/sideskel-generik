<?php

namespace App\Filament\Clusters\Desa\Resources\KeputusanResource\Pages;

use App\Filament\Clusters\Desa\Resources\KeputusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeputusans extends ListRecords
{
    protected static string $resource = KeputusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
