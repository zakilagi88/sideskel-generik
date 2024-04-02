<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKesehatanAnaks extends ListRecords
{
    protected static string $resource = KesehatanAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
