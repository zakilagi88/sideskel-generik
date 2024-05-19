<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HalamanDesa extends Cluster
{

    protected static ?string $clusterBreadcrumb = 'Desa Kelurahan';

    protected static ?string $navigationIcon = 'fas-folder-open';

    protected static ?string $navigationLabel = 'Data Desa Kelurahan';

    protected static ?string $slug = 'deskel';

    public function mount(): void
    {
        foreach ($this->getCachedSubNavigation() as $navigationGroup) {
            foreach ($navigationGroup->getItems() as $navigationItem) {
                redirect($navigationItem->getUrl());

                return;
            }
        }

        $this->redirect((static::getClusteredComponents()[0]::getUrl()));
    }
}