<?php

namespace App\Filament\Pages;

use App\Filament\Widgets;

use App\Filament\Widgets\StatsOverview;
use App\Livewire\Widgets\Chart\PekerjaanChart;
use App\Livewire\Widgets\Chart\PendudukApexBarChart;
use App\Livewire\Widgets\Chart\PendudukChart;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class Dashboard extends BasePage
{
    protected static string $view = 'filament.pages.dashboard';

    protected ?string $user, $kelurahan;


    public function __construct()
    {
        $this->user = ucwords(Filament::auth()->user()->name);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }

    public function getTitle(): string | Htmlable
    {
        return __("{$this->user} Dashboard");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('Selamat Datang');
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getFooter(): ?View
    {
        return view('filament.pages.custom-footer');
    }

    public function getWidgets(): array
    {
        return [
            PekerjaanChart::class,
            PendudukChart::class,
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return '8xl';
    }
}
