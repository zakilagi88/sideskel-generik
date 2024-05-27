<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HalamanArsip extends Cluster
{

    protected static ?string $clusterBreadcrumb = 'Arsip';

    protected static ?string $navigationLabel = 'Halaman Arsip';

    protected static ?string $slug = 'arsip';

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
