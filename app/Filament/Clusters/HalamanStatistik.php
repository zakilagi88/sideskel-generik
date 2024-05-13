<?php

namespace App\Filament\Clusters;

use App\Models\StatSDMistik;
use Filament\Clusters\Cluster;

class HalamanStatistik extends Cluster
{

    protected static ?string $slug = 'statistik';

    protected static ?string $clusterBreadcrumb = 'Statistik';

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
