<?php

namespace App\Livewire\Widgets\Tabel;

use App\Models\Penduduk;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Pekerjaan extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    #[Computed()]
    public function pekerjaans()
    {
        $pdd = Penduduk::allPekerjaan()->searchPekerjaan($this->search)->paginate($this->perPage);

        $this->dispatch('pekerjaans', $pdd);

        return $pdd;
    }

    public function render()
    {
        return view('livewire.widgets.tabel.pekerjaan', []);
    }
}