<?php

namespace App\Filament\Resources\RWResource\Pages;

use App\Filament\Resources\RWResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListRWS extends ListRecords
{
    protected static string $resource = RWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Rukun Warga';
    }
}
