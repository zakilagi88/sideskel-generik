<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanBerita\Resources\BeritaResource;
use App\Livewire\Templates\ListPage;
use App\Models\KategoriBerita;

class BeritaPage extends ListPage
{
    protected static string $resource = BeritaResource::class;

    protected static string $heading = 'Berita';

    public $kategoris;

    public function mount(KategoriBerita $kategoris)
    {
        $this->kategoris = $kategoris->whereHas('beritas', fn ($query) => $query->published())->get();
    }

    public function getViewData()
    {
        return [
            'heading' => static::getPageHeading(),
            'breadcrumb' => static::getPageBreadcrumb(),
            'kategoris' => $this->kategoris,
        ];
    }
}