<?php

namespace App\Filament\Resources\Shield\RoleResource\Pages;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Shield\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->button()->label('Tambah Peran'),
            Actions\Action::make('kembali')->label('Kembali Beranda')->url(Dashboard::getUrl())->color('secondary'),
        ];
    }
}