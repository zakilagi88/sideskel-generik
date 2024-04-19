<?php

namespace App\Livewire\Stat;

use App\Models\Stat;
use App\Models\StatKategori;
use Livewire\Component;

class Index extends Component
{

    public $stats;

    public $kategori;

    public function mount(Stat $stats): void
    {
        $this->stats = $stats->all();
    }

    public function render()
    {
        return view('livewire.stat.index');
    }
}
