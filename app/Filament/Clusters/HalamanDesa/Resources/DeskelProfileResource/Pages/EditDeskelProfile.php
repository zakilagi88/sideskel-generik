<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeskelProfile extends EditRecord
{
    protected static string $resource = DeskelProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
