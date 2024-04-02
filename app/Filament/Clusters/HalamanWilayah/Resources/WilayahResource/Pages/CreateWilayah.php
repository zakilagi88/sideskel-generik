<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages;

use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWilayah extends CreateRecord
{
    protected static string $resource = WilayahResource::class;
}
