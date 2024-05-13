<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKeamananDanLingkungans extends ManageRecords
{
    protected static string $resource = KeamananDanLingkunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
