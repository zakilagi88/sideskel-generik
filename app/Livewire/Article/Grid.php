<?php

namespace App\Livewire\Article;

use App\Models\Article;
use Livewire\Component;
use Illuminate\Support\Str;

class Grid extends Component
{
    public function render()
    {
        $articles = Article::published()->limit(10)->get();
        foreach ($articles as $article) {
            $article->body = $this->limitwords($article->body, 350, ' ...');
        }

        return view('livewire.article.grid', compact('articles'));
    }

    public function limitwords($value, $limit = 100, $end = '...')
    {
        if (Str::length($value) <= $limit) {
            return $value;
        }

        return Str::limit($value, $limit, $end);
    }
}