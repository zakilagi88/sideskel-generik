<?php

namespace App\Filament\Clusters\Desa;

use Filament\Clusters\Cluster;

class HalamanDesa extends Cluster
{

    protected static ?string $clusterBreadcrumb = 'Desa';

    protected static ?string $navigationIcon = 'fas-folder-open';

    protected static ?string $navigationLabel = 'Data Desa';

    protected static ?string $slug = 'desa';
}
