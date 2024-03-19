<?php

namespace App\Filament\Clusters\Desa\Resources\AparaturResource\Pages;

use App\Filament\Clusters\Desa\Resources\AparaturResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAparaturs extends ListRecords
{
    protected static string $resource = AparaturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
