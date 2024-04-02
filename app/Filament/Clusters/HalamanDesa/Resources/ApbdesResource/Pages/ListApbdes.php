<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\ApbdesResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\ApbdesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApbdes extends ListRecords
{
    protected static string $resource = ApbdesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
