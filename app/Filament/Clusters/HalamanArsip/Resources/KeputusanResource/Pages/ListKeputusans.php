<?php

namespace App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource\Pages;

use App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListKeputusans extends ListRecords
{
    protected static string $resource = KeputusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data'),
        ];
    }
}