<?php

namespace App\Livewire\Widgets;

use Livewire\Component;

class TableList extends Component
{

    public $items;

    public function mount($items)
    {
        $this->items = $items;
    }


    public function render()
    {
        return view('livewire.widgets.table-list');
    }
}