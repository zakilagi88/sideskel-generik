<?php

namespace App\Filament\Clusters\HalamanArsip\Resources\PeraturanResource\Pages;

use App\Filament\Clusters\HalamanArsip\Resources\PeraturanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeraturan extends EditRecord
{
    protected static string $resource = PeraturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
