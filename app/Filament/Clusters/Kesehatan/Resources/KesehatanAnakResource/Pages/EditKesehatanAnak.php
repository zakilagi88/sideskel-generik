<?php

namespace App\Filament\Clusters\Kesehatan\Resources\KesehatanAnakResource\Pages;

use App\Filament\Clusters\Kesehatan\Resources\KesehatanAnakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKesehatanAnak extends EditRecord
{
    protected static string $resource = KesehatanAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
