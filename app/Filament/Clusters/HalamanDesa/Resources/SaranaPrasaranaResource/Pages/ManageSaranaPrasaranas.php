<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSaranaPrasaranas extends ManageRecords
{
    protected static string $resource = SaranaPrasaranaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
