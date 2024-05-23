<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HalamanKependudukan extends Cluster
{
    protected static ?string $slug = 'kependudukan';

    protected static ?string $clusterBreadcrumb = 'Kependudukan';

    protected static ?string $navigationIcon = 'fas-people-roof';

    protected static ?string $navigationLabel = 'Data Kependudukan';

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
