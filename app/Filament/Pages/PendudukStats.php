<?php

namespace App\Filament\Pages;

use App\Livewire\Widgets\Chart\PendudukApexBarChart;
use App\Livewire\Widgets\Chart\PendudukChart;
use App\Models\Penduduk;
use App\Models\Statistik;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class PendudukStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Statistik Penduduk';

    protected static string $view = 'filament.pages.components.penduduk-stats';

    private $statistik;

    public function __construct()
    {
        $this->statistik = Statistik::all();
    }
}