<?php

namespace App\Livewire\Widgets;

use Livewire\Component;

class StepPreparation extends Component
{
    public $step;

    public function mount($step)
    {
        $this->step = $step;
    }

    public function nextStep($stepId)
    {
        $this->dispatch('next-step', $stepId)->to(SistemPreparation::class);
    }

    public function render()
    {
        return view('livewire.widgets.step-preparation');
    }
}
