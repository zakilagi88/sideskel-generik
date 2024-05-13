<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;
use App\Livewire\Templates\ListPage;

class BeritaPage extends ListPage
{
    protected static string $resource = BeritaResource::class;

    protected static string $heading = 'Berita';
}
