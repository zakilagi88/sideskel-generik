<?php

namespace App\Livewire;

use Filament\Widgets\Widget;

class InfoCard extends Widget
{
    protected static string $view = 'livewire.widgets.info-card';

    public $stats = [];

    protected int | string | array $columnSpan = 2;
}
