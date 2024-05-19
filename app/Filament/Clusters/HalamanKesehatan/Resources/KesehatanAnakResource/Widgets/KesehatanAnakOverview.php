<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Widgets;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages\ListKesehatanAnaks;
use App\Models\KesehatanAnak;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KesehatanAnakOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ListKesehatanAnaks::class;
    }

    protected function getStats(): array
    {
        return [
            // Stat::make('Kategori Status Stunting', $this->getPageTableQuery()->count())
            //     ->chart(
            //         [
            //             20, 10, 3, 12, 1, 14, 10, 1, 4, 20
            //         ]
            //     )->color('primary')->icon('fas-people-group'),
            // Stat::make('RW', KesehatanAnak::tree()->get()->where('depth', 0)->count())
            //     ->icon('heroicon-o-user-group')
            //     ->description('Jumlah RW')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->chartColor('primary')
            //     ->color('primary')
            //     ->chart([
            //         20, 10, 3, 12, 1, 14, 10, 1, 4, 20
            //     ])
            //     ->extraAttributes([
            //         'class' => 'cursor-pointer',
            //     ]),
            // Stat::make('RT', KesehatanAnak::tree()->get()->where('depth', 1)->count())
            //     ->icon('heroicon-o-user-group')
            //     ->description('Jumlah RT')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->descriptionColor('success')
            //     ->chartColor('success')
            //     ->color('success')
            //     ->chart([
            //         20, 10, 3, 12, 1, 14, 10, 1, 4, 20
            //     ])
            //     ->extraAttributes([
            //         'class' => 'cursor-pointer',
            //     ]),

        ];
    }
}
