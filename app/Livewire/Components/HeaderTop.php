<?php

namespace App\Livewire\Components;

use App\Models\Deskel\DesaKelurahanProfile;
use App\Settings\GeneralSettings;
use Livewire\Component;

class HeaderTop extends Component
{
    public $deskel;

    public ?array $data = [];

    public function mount(DesaKelurahanProfile $deskel)
    {
        $this->deskel = $deskel::first(['deskel_id', 'email', 'telepon']);

        $this->fillData();
    }

    protected function fillData(): array
    {
        $generalSettings = app(GeneralSettings::class);

        return $this->data = $generalSettings->toArray();
    }

    public function render()
    {
        return view('livewire.components.header-top', $this->data);
    }
}
