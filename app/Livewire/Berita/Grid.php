<?php

namespace App\Livewire\Berita;

use App\Models\Web\Berita;
use Livewire\Component;
use Illuminate\Support\Str;

class Grid extends Component
{
    public function render()
    {
        $beritas = Berita::published()->limit(10)->get();
        foreach ($beritas as $berita) {
            $berita->body = $this->limitwords($berita->body, 350, ' ...');
        }

        return view('livewire.berita.grid', compact('beritas'));
    }

    public function limitwords($value, $limit = 100, $end = '...')
    {
        if (Str::length($value) <= $limit) {
            return $value;
        }

        return Str::limit($value, $limit, $end);
    }
}
