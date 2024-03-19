<?php

namespace App\Filament\Clusters\Desa\Resources\KeputusanResource\Pages;

use App\Filament\Clusters\Desa\Resources\KeputusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeputusan extends EditRecord
{
    protected static string $resource = KeputusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
