<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPenduduk extends ViewRecord
{
    protected static string $resource = PendudukResource::class;

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
