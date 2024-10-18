<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\JabatanResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Clusters\HalamanDesa\Resources\JabatanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJabatans extends ManageRecords
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            Actions\CreateAction::make()->label('Tambah Data'),
            Actions\Action::make('Data Aparatur')
                ->icon('fas-cogs')
                ->url(fn(): string => AparaturResource::getUrl())
                ->button(),
        ];
    }
}
