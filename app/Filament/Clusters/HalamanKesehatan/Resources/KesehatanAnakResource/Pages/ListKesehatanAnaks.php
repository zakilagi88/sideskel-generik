<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource;
use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Widgets\KesehatanAnakOverview;
use App\Models\KesehatanAnak;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKesehatanAnaks extends ListRecords
{
    protected static string $resource = KesehatanAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // KesehatanAnakOverview::class,
        ];
    }
}
