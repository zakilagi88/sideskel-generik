<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;


class HalamanBerita extends Cluster
{
    protected static ?string $slug = 'berita';

    protected static ?string $clusterBreadcrumb = 'Berita';

    protected static ?string $navigationGroup = 'Berita';

    protected static ?string $navigationLabel = 'Berita';
}
