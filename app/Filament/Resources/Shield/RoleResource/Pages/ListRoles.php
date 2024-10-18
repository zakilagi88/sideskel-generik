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
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            Actions\CreateAction::make()->button()->label('Tambah Peran'),
        ];
    }
}
