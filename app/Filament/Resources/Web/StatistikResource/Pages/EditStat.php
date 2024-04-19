<?php

namespace App\Filament\Resources\Web\StatResource\Pages;

use App\Filament\Resources\Web\StatResource;
use App\Models\Stat;
use App\Models\StatKategori;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Reactive;

class EditStat extends EditRecord
{

    protected static string $resource = StatResource::class;

    protected static string $view = 'filament.clusters.stat.page';

    public $kategori, $activeTab;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->kategori = StatKategori::all();
    }

    protected function getHeaderActions(): array
    {
        return [
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
