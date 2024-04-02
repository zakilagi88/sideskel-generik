<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\KeputusanResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\KeputusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditKeputusan extends EditRecord
{
    protected static string $resource = KeputusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
