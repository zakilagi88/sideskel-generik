<?php

namespace App\Filament\Clusters\Berita\Resources\BeritaResource\Pages;

use App\Filament\Clusters\Berita\Resources\BeritaResource;;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
