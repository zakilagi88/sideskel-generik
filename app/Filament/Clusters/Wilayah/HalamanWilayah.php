<?php

namespace App\Filament\Clusters\Wilayah;

use App\Filament\Clusters\Wilayah\Pages\GenerateWilayah;
use Filament\Clusters\Cluster;

class HalamanWilayah extends Cluster
{

    protected static ?string $navigationIcon = 'fas-map';

    protected static ?string $clusterBreadcrumb = 'Kewilayahan';

    protected static ?string $slug = 'wilayah';

    protected static ?int $navigationSort = 0;

    public function mount(): void
    {
        redirect(GenerateWilayah::getUrl());
    }
}
