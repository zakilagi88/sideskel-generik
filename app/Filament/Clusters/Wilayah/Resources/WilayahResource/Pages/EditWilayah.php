<?php

namespace App\Filament\Clusters\Wilayah\Resources\WilayahResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\WilayahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWilayah extends EditRecord
{
    protected static string $resource = WilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
