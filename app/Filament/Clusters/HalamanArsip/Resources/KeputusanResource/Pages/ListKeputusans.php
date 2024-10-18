<?php

namespace App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource\Pages;

use App\Filament\Clusters\HalamanArsip\Resources\KeputusanResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListKeputusans extends ListRecords
{
    protected static string $resource = KeputusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            Actions\CreateAction::make()->label('Tambah Data'),
        ];
    }
}
