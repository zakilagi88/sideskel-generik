<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSaranaPrasaranas extends ManageRecords
{
    protected static string $resource = SaranaPrasaranaResource::class;

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
