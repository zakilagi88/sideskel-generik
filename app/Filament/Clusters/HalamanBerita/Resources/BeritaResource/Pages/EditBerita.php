<?php

namespace App\Filament\Clusters\HalamanBerita\Resources\BeritaResource\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

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
