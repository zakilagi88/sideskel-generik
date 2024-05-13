<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HalamanWilayah extends Cluster
{

    protected static ?string $navigationIcon = 'fas-map';

    protected static ?string $clusterBreadcrumb = 'Kewilayahan';

    protected static ?string $slug = 'index';

    protected static ?int $navigationSort = 0;

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
