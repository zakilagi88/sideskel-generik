<?php

namespace App\Livewire\Pages\Berita;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;
use App\Livewire\Templates\SimplePage;

class BeritaDisplay extends SimplePage
{
    protected static string $resource = BeritaResource::class;

    protected static string $heading = 'Berita';
}
