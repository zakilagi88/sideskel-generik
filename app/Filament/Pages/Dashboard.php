<?php

namespace App\Filament\Pages;

use App\Facades\Deskel;
use App\Livewire\Widgets\JadwalKegiatanWidget;
use App\Livewire\Widgets\SistemPreparation;
use App\Livewire\Widgets\StatsOverview;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BasePage;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Widgets\AccountWidget;
use Illuminate\Contracts\View\View;

class Dashboard extends BasePage
{

    use HasFiltersAction;

    protected static string $routePath = 'beranda';

    protected ?string $user, $kelurahan;


    public function __construct()
    {
        $this->user = ucwords(Filament::auth()->user()->name);
    }

    protected function getHeaderWidgets(): array
    {
        $cek = (Deskel::getFacadeRoot()->status);
        if ($cek == true) {
            return [
                AccountWidget::class,
                StatsOverview::class
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

    // public function getTitle(): string | Htmlable
    // {
    //     return __("{$this->user} Dashboard");
    // }

    // public function getSubheading(): string|Htmlable|null
    // {
    //     return __('Selamat Datang');
    // }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getFooter(): ?View
    {
        return view('filament.pages.custom-footer');
    }


    public function getMaxContentWidth(): ?string
    {
        return '8xl';
    }
}
