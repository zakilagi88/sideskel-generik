<?php

namespace App\Livewire\Pages;

use App\Models\Statistik;
use Livewire\Component;

class StatsGrid extends Component
{
    public function render()
    {

        $stats = Statistik::all();

        return view('livewire.pages.stats-grid', compact('stats'));
    }
}