<?php

namespace App\Filament\Resources\RWResource\Pages;

use App\Filament\Resources\RWResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRW extends CreateRecord
{
    protected static string $resource = RWResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
