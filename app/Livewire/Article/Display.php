<?php

namespace App\Livewire\Article;

use App\Models\Article;
use Livewire\Component;

class Display extends Component
{

    public Article $article;

    public function render()
    {
        return view('livewire.article.display');
    }
}
