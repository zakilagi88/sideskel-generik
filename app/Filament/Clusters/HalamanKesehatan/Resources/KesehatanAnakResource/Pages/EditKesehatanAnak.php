<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKesehatanAnak extends EditRecord
{
    protected static string $resource = KesehatanAnakResource::class;

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
