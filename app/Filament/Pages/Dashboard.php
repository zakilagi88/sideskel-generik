<?php

namespace App\Filament\Pages;

use App\Facades\Deskel;
use App\Livewire\Widgets\JadwalKegiatanWidget;
use App\Livewire\Widgets\SistemPreparation;
use App\Livewire\Widgets\StatsOverview;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BasePage;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Widgets\AccountWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class Dashboard extends BasePage
{

    use HasFiltersAction;

    protected static string $routePath = 'beranda';

    protected function getHeaderWidgets(): array
    {
        $settings = app(GeneralSettings::class)->toArray();

        if ($settings['site_active'] == true) {
            return [
                AccountWidget::class,
                StatsOverview::class,
                JadwalKegiatanWidget::class,
            ];
        } else {
            return [
                AccountWidget::class,
                SistemPreparation::class,
            ];
        }
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }

    public function getTitle(): string | Htmlable
    {
        return __("Beranda");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('Halaman Beranda');
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getFooter(): ?View
    {
        return view('filament.pages.components.footer');
    }

    public function getMaxContentWidth(): ?string
    {
        return '8xl';
    }
}
