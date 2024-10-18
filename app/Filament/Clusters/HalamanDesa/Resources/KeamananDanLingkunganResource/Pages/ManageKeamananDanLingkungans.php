<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKeamananDanLingkungans extends ManageRecords
{
    protected static string $resource = KeamananDanLingkunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl())
        ];
    }
}
