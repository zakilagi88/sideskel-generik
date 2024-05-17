<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDeskelProfile extends ViewRecord
{
    protected static string $resource = DeskelProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
            Actions\EditAction::make(),
        ];
    }
}
