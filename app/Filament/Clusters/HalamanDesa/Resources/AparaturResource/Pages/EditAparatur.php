<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\AparaturResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAparatur extends EditRecord
{
    protected static string $resource = AparaturResource::class;

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
