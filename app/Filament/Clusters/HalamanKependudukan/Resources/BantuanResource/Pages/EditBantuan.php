<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBantuan extends EditRecord
{
    protected static string $resource = BantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
