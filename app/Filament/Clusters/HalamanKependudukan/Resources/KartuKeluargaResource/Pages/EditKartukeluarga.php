<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Models\KartuKeluarga;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartukeluarga extends EditRecord
{
    protected static string $resource = KartukeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected $listeners = [
        'auditRestored',
        'updateAuditsRelationManager',
    ];

    public function auditRestored()
    {
        // your code
    }

    public function updated()
    {
        $this->dispatch('updateAuditsRelationManager');
    }
}
