<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLembaga extends CreateRecord
{
    protected static string $resource = LembagaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        return $record;
    }
}
