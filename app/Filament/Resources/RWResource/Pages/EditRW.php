<?php

namespace App\Filament\Resources\RWResource\Pages;

use App\Filament\Resources\RWResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRW extends EditRecord
{
    protected static string $resource = RWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
