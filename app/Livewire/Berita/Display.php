<?php

namespace App\Livewire\Berita;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;
use App\Livewire\Templates\SimplePage;

class Display extends SimplePage
{
    protected static string $resource = BeritaResource::class;
}
