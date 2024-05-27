<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;


class HalamanBerita extends Cluster
{
    protected static ?string $slug = 'berita';

    protected static ?string $clusterBreadcrumb = 'Berita';

    protected static ?string $navigationGroup = 'Berita';

    protected static ?string $navigationLabel = 'Berita';

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