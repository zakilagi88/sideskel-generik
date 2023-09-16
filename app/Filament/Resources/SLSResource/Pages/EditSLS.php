<?php

namespace App\Filament\Resources\SLSResource\Pages;

use App\Filament\Resources\SLSResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSLS extends EditRecord
{
    protected static string $resource = SLSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
