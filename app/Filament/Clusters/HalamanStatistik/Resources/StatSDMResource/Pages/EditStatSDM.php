<?php

namespace App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource\Pages;

use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource;
use App\Models\StatKategori;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Collection;

class EditStatSDM extends EditRecord
{

    protected static string $resource = StatSDMResource::class;

    public $kategori, $activeTab;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->kategori = StatKategori::all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getStatKategori(): Collection
    {
        return $this->kategori->all();
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
