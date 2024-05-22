<?php

namespace App\Filament\Resources\Shield\UserResource\Pages;

use App\Filament\Resources\Shield\UserResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->button()->label('Tambah Pengguna'),
            Actions\Action::make('Kembali ke Beranda')->url(Dashboard::getUrl())->color('danger')
        ];
    }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
}
