<?php

namespace App\Livewire\Components;

use App\Settings\WebSettings;
use Livewire\Component;

class Header extends Component
{

    protected static string $settings = WebSettings::class;

    public ?array $data = [];

    public function mount()
    {
        $this->fillData();
    }

    protected function fillData(): array
    {
        $settings = app(static::getSettings());

        return $this->data = $settings->toArray();
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
        return view('livewire.components.header', $this->data);
    }
}
