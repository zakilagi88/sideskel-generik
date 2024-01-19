<?php

namespace App\Livewire\Berita;

use App\Models\Berita;
use Livewire\Component;

class Display extends Component
{

    public Berita $berita;

    public function render()
    {
        return view('livewire.berita.display');
    }
}