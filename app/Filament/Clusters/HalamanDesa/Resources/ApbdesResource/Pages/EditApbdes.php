<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\ApbdesResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\ApbdesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApbdes extends EditRecord
{
    protected static string $resource = ApbdesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
