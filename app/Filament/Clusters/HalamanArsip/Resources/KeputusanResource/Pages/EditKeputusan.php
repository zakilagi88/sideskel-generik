<?php

namespace App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource\Pages;

use App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditKeputusan extends EditRecord
{
    protected static string $resource = KeputusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
            Actions\DeleteAction::make(),
        ];
    }

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     dd($record, $data);
    //     $record->update($data);

    //     return $record;
    // }
}
