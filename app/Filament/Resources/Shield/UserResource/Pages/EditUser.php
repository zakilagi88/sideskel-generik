<?php

namespace App\Filament\Resources\Shield\UserResource\Pages;

use App\Filament\Resources\Shield\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class EditUser extends EditRecord
{

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->hidden(fn () => $this->record->username === 'admin'),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
