<?php

namespace App\Livewire\Widgets\Tables\Dinamika;

use App\Services\RekapitulasiService;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Livewire\Attributes\Reactive;

class DinamikaTable extends TableWidget
{

    protected int | string | array $columnSpan = 'full';

    #[Reactive]
    public array $filterData = [];

    public function table(Table $table): Table
    {
        $bulan = $this->filterData['bulan'] ?? date('m');
        $namaBulan = [
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
        ];
        $bulan = $namaBulan[$bulan];
        $tahun = $this->filterData['tahun'] ?? date('Y');
        /** @var \App\Models\User */
        $wilayah = Filament::auth()->user()->wilayah_id;

        $query = new RekapitulasiService();
        return $table
            ->query(fn () => $query->getRekapitulasiQuery($bulan, $tahun, $wilayah))
            ->heading('Dinamika Penduduk Bulan ' . $bulan . ' Tahun ' . $tahun)
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
            ->paginated(false);
    }
}
