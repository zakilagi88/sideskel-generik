<?php

namespace App\Livewire;

use App\Facades\Deskel;
use App\Models\DesaKelurahanProfile;
use App\Settings\GeneralSettings;
use App\Settings\WebSettings;
use Livewire\Component;

class Home extends Component
{
    public $prov, $kabkota, $kec, $desa, $deskel;

    public ?array $data = [];

    public function mount()
    {
        $this->deskel = DesaKelurahanProfile::with(['prov', 'kabkota', 'kec', 'dk'])->first();

        $this->data = array_merge_recursive(app(GeneralSettings::class)->toArray(), app(WebSettings::class)->toArray());
    }

    public function render()
    {
        return view('livewire.home', $this->data);
    }
}
