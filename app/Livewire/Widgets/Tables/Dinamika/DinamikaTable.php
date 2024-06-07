<?php

namespace App\Livewire\Widgets\Tables\Dinamika;

use App\Models\{Penduduk\PendudukAgama, RekapitulasiBulanan};
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;

class DinamikaTable extends TableWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $bulan = date('m');
        $tahun = date('Y');
        $wilayah = null;
        return $table
            ->query(RekapitulasiBulanan::getRekapitulasi($bulan, $tahun, $wilayah))
            ->heading('Dinamika Penduduk Bulan Ini')
            ->queryStringIdentifier('dinamika')
            ->columns([
                TextColumn::make('id')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter(),
                TextColumn::make('Perincian')
                    ->label('Perincian')
                    ->alignLeft(),
                TextColumn::make('Laki_Laki')
                    ->label('Laki-laki')
                    ->alignCenter(),
                TextColumn::make('Perempuan')
                    ->label('Perempuan')
                    ->alignCenter(),
                TextColumn::make('Total')
                    ->label('Total')
                    ->alignCenter(),
            ])
            ->deferLoading()
            ->deferFilters()
            ->filters(
                [
                    Filter::make('waktu')
                        ->form([
                            Select::make('bulan')
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
                                ->options([
                                    '2021' => '2021',
                                    '2022' => '2022',
                                    '2023' => '2023',
                                    '2024' => '2024',
                                    '2025' => '2025',
                                ])
                                ->label('Tahun'),

                        ])
                        ->columns(2)->columnSpanFull(),

                ],
                FiltersLayout::AboveContent
            )
            ->filtersFormColumns(2)
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->paginated(false);
    }
}
