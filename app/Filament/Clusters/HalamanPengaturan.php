<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HalamanPengaturan extends Cluster
{
    protected static ?string $navigationIcon = 'fas-cogs';

    protected static ?string $clusterBreadcrumb = 'Pengaturan Hak Akses';

    protected static ?string $slug = 'pengaturan';

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
