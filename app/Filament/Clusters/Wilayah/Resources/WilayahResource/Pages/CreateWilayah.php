<?php

namespace App\Filament\Clusters\Wilayah\Resources\WilayahResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\WilayahResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWilayah extends CreateRecord
{
    protected static string $resource = WilayahResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
