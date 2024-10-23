<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource\Widgets;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;

class FilterDinamika extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.clusters.halaman-kependudukan.resources.dinamika-resource.widgets.filter-dinamika';
    protected int | string | array $columnSpan = 'full';

    public ?array $filterData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('bulan')
                    ->selectablePlaceholder(false)
                    ->default(date('m'))
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ])
                    ->label('Bulan'),
                Select::make('tahun')
                    ->selectablePlaceholder(false)
                    ->default(date('Y'))
                    ->options(
                        array_combine(
                            range(2021, date('Y')),
                            range(2021, date('Y'))
                        )
                    )
                    ->label('Tahun'),
            ])
            ->columns(2)
            ->statePath('filterData');
    }

    public function save(): void
    {
        $this->dispatch('filterUpdated', $this->filterData);
    }
}
