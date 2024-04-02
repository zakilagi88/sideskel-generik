<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\PeraturanResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\PeraturanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeraturans extends ListRecords
{
    protected static string $resource = PeraturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
