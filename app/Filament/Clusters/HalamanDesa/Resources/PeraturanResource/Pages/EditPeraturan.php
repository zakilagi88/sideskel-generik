<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\PeraturanResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\PeraturanResource;
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
