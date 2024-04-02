<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages;

use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditWilayah extends EditRecord
{
    protected static string $resource = WilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }
}
