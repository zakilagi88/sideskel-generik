<?php

namespace App\Livewire\Widgets\Tables\Dinamika;

use App\Models\{Penduduk\PendudukAgama, RekapitulasiBulanan};
use Filament\Tables\Columns\TextColumn;
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
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->paginated(false)
            ->striped();
    }
}
