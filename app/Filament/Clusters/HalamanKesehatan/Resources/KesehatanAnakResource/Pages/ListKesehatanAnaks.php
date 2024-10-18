<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource;
use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Widgets\KesehatanAnakOverview;
use App\Filament\Pages\Dashboard;
use App\Models\KesehatanAnak;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKesehatanAnaks extends ListRecords
{
    protected static string $resource = KesehatanAnakResource::class;

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

    protected function getHeaderWidgets(): array
    {
        return [
            // KesehatanAnakOverview::class,
        ];
    }
}