<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLembaga extends EditRecord
{
    protected static string $resource = LembagaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
