<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HalamanKesehatan extends Cluster
{
    protected static ?string $slug = 'kesehatan';

    protected static ?string $clusterBreadcrumb = 'Kesehatan';

    protected static ?string $navigationIcon = 'fas-heart-pulse';

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
