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
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->label('Bulan'),
                Select::make('tahun')
                    ->selectablePlaceholder(false)
                    ->default(date('Y'))
                    ->options([
                        '2021' => '2021',
                        '2022' => '2022',
                        '2023' => '2023',
                        '2024' => '2024',
                        '2025' => '2025',
                    ])
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
