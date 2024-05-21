<?php

namespace App\Livewire\Components;

use App\Models\Deskel\DesaKelurahanProfile;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserInfoCard extends Component
{

    public $deskel;

    public $user;

    public ?array $data = [];

    public function mount(DesaKelurahanProfile $deskel)
    {
        $this->deskel = $deskel::first(['deskel_id']);

        $this->fillData();
    }

    protected function fillData(): array
    {

        $generalSettings = app(GeneralSettings::class);

        return $this->data = $generalSettings->toArray();
    }


    public function render()
    {
        return view('livewire.components.user-info-card', $this->data);
    }
}
