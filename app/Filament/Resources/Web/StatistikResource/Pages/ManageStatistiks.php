<?php

namespace App\Filament\Resources\Web\StatistikResource\Pages;

use App\Filament\Resources\Web\StatistikResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatistiks extends ManageRecords
{
    protected static string $resource = StatistikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
