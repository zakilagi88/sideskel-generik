<?php

namespace App\Livewire;

use App\Facades\Deskel;
use Livewire\Component;

class Home extends Component
{
    public $prov, $kabkota, $kec, $desa, $deskel;

    public function mount()
    {
        $this->deskel = Deskel::getFacadeRoot();
        $this->prov = $this->deskel->dk?->kec?->kabkota?->prov?->prov_nama ?? null;
        $this->kabkota = $this->deskel->dk?->kec?->kabkota?->kabkota_nama ?? null;
        $this->kec =  $this->deskel->dk?->kec?->kec_nama ?? null;
    }

    public function render()
    {
        return view('livewire.home');
    }
}
