<?php

namespace App\Livewire\Stat;

use App\Models\StatKategori;
use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class StatDisplay extends Component
{

    public ?array $komponen = [];
    public $kategori;
    public $activeTab;


    protected static string $resource = StatSDMResource::class;

    protected static string $heading = 'Statistik Penduduk';

    public Model | int | string | null $record;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->kategori = StatKategori::all();
    }

    protected function resolveRecord(int | string $key): Model
    {

        $record = static::getResource()::resolveRecordRouteBinding($key);

        return $record;
    }

    public static function getResource(): string
    {
        return static::$resource;
    }

    protected function getPageHeading(): string
    {
        return $this->record->title ?? static::$heading;
    }

    public function render()
    {
        return view('livewire.stat.stat-display');
    }
}