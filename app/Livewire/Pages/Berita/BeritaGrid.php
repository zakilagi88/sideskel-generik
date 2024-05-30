<?php

namespace App\Livewire\Pages\Berita;

use App\Models\Web\Berita;
use Livewire\Component;
use Illuminate\Support\Str;

class BeritaGrid extends Component
{

    public function render()
    {
        $beritas = Berita::with(['author', 'kategori', 'tags'])->published()->limit(7)->get();
        foreach ($beritas as $berita) {
            $berita->body = $this->limitwords($berita->body, 350, ' ...');
        }

        return view('livewire.pages.berita.berita-grid', compact('beritas'));
    }

    public function placeholder(array $params = [])
    {
        return view('livewire.components.skeleton-grid', $params);
    }

    public function limitwords($value, $limit = 100, $end = '...')
    {
        if (Str::length($value) <= $limit) {
            return $value;
        }

        return Str::limit($value, $limit, $end);
    }
}
