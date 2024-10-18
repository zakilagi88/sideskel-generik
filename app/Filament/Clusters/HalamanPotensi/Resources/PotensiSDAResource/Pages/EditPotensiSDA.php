<?php

namespace App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource\Pages;

use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPotensiSDA extends EditRecord
{
    protected static string $resource = PotensiSDAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
        ];
    }
}