<?php

namespace App\Livewire\Stat;

use App\Models\Stat;
use App\Models\StatKategori;
use App\Enums\Kependudukan\AgamaType;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\Alignment;
use Illuminate\Http\Request;
use Livewire\Component;

class StatDisplay extends Component
{

    public $stat;
    public ?array $komponen = [];
    public $kategori;
    public $activeTab;


    public function mount(Stat $stat, StatKategori $kategori): void
    {
        $this->stat = $stat;
        $this->kategori = $kategori->all();
    }



    public function render()
    {
        return view('livewire.stat.stat-display');
    }
}