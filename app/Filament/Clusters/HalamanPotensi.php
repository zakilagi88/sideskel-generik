<?php

namespace App\Filament\Clusters;

use App\Models\StatSDMistik;
use Filament\Clusters\Cluster;

class HalamanPotensi extends Cluster
{

    protected static ?string $slug = 'potensi';

    protected static ?string $clusterBreadcrumb = 'Potensi';

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
