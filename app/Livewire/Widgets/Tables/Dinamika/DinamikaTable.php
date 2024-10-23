<?php

namespace App\Livewire\Widgets\Tables\Dinamika;

use App\Filament\Exports\RekapitulasiExporter;
use App\Services\RekapitulasiService;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\ColumnGroup;
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
        $tahun = $this->filterData['tahun'] ?? date('Y');
        /** @var \App\Models\User */
        $wilayah = Filament::auth()->user()->wilayah_id;

        $query = new RekapitulasiService();
        return $table
            ->query(fn() => $query->getRekapitulasiQuery($bulan, $tahun, $wilayah))
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
                ColumnGroup::make(
                    'WNA',
                    [
                        TextColumn::make('WNA_lk')
                            ->label('Laki-laki')
                            ->alignCenter(),
                        TextColumn::make('WNA_pr')
                            ->label('Perempuan')
                            ->alignCenter(),
                        TextColumn::make('WNA_total')
                            ->label('Total')
                            ->alignCenter(),
                    ]
                )
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),
                ColumnGroup::make(
                    'WNI',
                    [
                        TextColumn::make('WNI_lk')
                            ->label('Laki-laki')
                            ->alignCenter(),
                        TextColumn::make('WNI_pr')
                            ->label('Perempuan')
                            ->alignCenter(),
                        TextColumn::make('WNI_total')
                            ->label('Total')
                            ->alignCenter(),
                    ]
                )
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),
                TextColumn::make('Total')
                    ->label('Total')
                    ->alignCenter(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(RekapitulasiExporter::class)
                    ->color('primary')
                    ->label('Unduh Data')
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->columnMapping(),
            ])
            ->deferLoading()
            ->deferFilters()
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->paginated(false);
    }
}
