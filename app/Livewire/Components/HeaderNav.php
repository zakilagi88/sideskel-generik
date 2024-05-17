<?php

namespace App\Livewire\Components;

use App\Facades\Deskel;
use App\Models\DesaKelurahanProfile;
use App\Settings\GeneralSettings;
use App\Settings\WebSettings;
use Livewire\Component;

class HeaderNav extends Component
{

    protected static string $settings = WebSettings::class;

    public $deskel;

    public ?array $data = [];

    public function mount(DesaKelurahanProfile $deskel)
    {
        $this->deskel = $deskel::first(['deskel_id', 'logo']);

        $this->fillData();
    }

    protected function fillData(): array
    {
        $settings = app(static::getSettings());

        $generalSettings = app(GeneralSettings::class);


        return $this->data = array_merge_recursive($settings->toArray(), $generalSettings->toArray());
    }


    public static function getSettings(): string
    {
        return  static::$settings ?? (string) str(class_basename(static::class))
            ->beforeLast('Settings')
            ->prepend('App\\Settings\\')
            ->append('Settings');
    }

    public function render()
    {
        return view('livewire.components.header-nav', $this->data);
    }
}