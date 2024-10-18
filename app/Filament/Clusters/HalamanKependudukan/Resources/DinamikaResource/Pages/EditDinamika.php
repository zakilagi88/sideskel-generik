<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDinamika extends EditRecord
{
    protected static string $resource = DinamikaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
            Actions\DeleteAction::make(),
        ];
    }
}
