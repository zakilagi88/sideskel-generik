<?php

namespace App\Filament\Clusters\Kependudukan;

use Filament\Clusters\Cluster;

class HalamanKependudukan extends Cluster
{
    protected static ?string $slug = 'kependudukan';

    protected static ?string $clusterBreadcrumb = 'Kependudukan';

    protected static ?string $navigationIcon = 'fas-people-roof';

    protected static ?string $navigationLabel = 'Data Kependudukan';
}
