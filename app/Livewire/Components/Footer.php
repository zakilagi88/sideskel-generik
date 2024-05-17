<?php

namespace App\Livewire\Components;

use App\Models\DesaKelurahanProfile;
use App\Settings\GeneralSettings;
use App\Settings\WebSettings;
use Livewire\Component;

class Footer extends Component
{
    public $deskel;

    public ?array $data = [];

    public function mount(DesaKelurahanProfile $deskel)
    {
        $this->deskel = $deskel::first(['deskel_id', 'logo', 'telepon', 'email', 'alamat']);

        $this->fillData();
    }

    protected function fillData(): array
    {

        $generalSettings = app(GeneralSettings::class);

        return $this->data = array_merge_recursive($generalSettings->toArray(), app((WebSettings::class))->toArray());
    }


    public function render()
    {
        return view('livewire.components.footer', $this->data);
    }
}
