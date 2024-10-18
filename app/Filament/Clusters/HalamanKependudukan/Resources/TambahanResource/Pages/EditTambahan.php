<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTambahan extends EditRecord
{
    protected static string $resource = TambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
            Actions\DeleteAction::make(),
        ];
    }
}
