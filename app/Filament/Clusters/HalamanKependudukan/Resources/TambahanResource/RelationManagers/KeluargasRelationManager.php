<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers;

use App\Filament\Exports\TambahanExporter;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Tambahanable;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\{AttachAction, BulkActionGroup, DetachAction, DetachBulkAction, ExportAction};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeluargasRelationManager extends RelationManager
{
    protected static string $relationship = 'keluargas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kk_kepala')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $operatorWilayah = Filament::auth()->user()->hasRole('Operator Wilayah');
        $monitorWilayah = Filament::auth()->user()->hasRole('Monitor Wilayah');
        return $table
            ->recordTitleAttribute('kk_id')
            ->heading('Data Keluarga Terdaftar Data Tambahan')
            ->columns([
                TextColumn::make('kepalaKeluarga.nama_lengkap')->label('Nama Kepala Keluarga'),
                TextColumn::make('tambahanable_ket')->label('Keterangan')->badge(),
                TextColumn::make('kepalaKeluarga.nik')->label('NIK'),
                TextColumn::make('kepalaKeluarga.nama_lengkap')->label('Nama'),
                TextColumn::make('kepalaKeluarga.alamat_sekarang')->label('Alamat'),
                TextColumn::make('kepalaKeluarga.jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('kepalaKeluarga.tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('kepalaKeluarga.tanggal_lahir')
                    ->label('Usia')
                    ->suffix(' Tahun')
            ])

            ->filters([
                SelectFilter::make('tambahanable_ket')
                    ->label('')
                    ->options(
                        function () {
                            $kategoris = $this->getOwnerRecord()->kategori;
                            $modifiedKeyValue = [];
                            foreach ($kategoris as $kategori) {
                                $normalizedKey = str_replace(' ', '-', strtolower($kategori));
                                $modifiedKeyValue[$normalizedKey] = $kategori;
                            }
                            return $modifiedKeyValue;
                        }
                    ),
            ], layout: FiltersLayout::AboveContent)
            ->deferLoading()
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->striped()
            ->headerActions([
                ExportAction::make()
                    ->exporter(TambahanExporter::class)
                    ->color('primary')
                    ->label('Ekspor Data')
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->columnMapping(),
                AttachAction::make()->label('Tambahkan Data Terpilih')
                    ->recordSelect(
                        fn (Select $select) =>
                        $select
                            ->multiple()
                            ->disableOptionWhen(
                                fn (string $value): bool => Tambahanable::query()
                                    ->where('tambahanable_type', KartuKeluarga::class)
                                    ->where('tambahanable_id', $value)
                                    ->exists()
                            )
                            ->searchable()
                            ->placeholder('Cari NIK atau Nama Lengkap...')
                            ->options(
                                fn () => KartuKeluarga::query()
                                    ->with(['wilayahs', 'kepalaKeluarga'])
                                    ->when($operatorWilayah, function ($query) {
                                        $query->where('wilayah_id', auth()->user()->wilayah_id);
                                    })
                                    ->when($monitorWilayah, function ($query) {
                                        $query->where('wilayah_id', auth()->user()->wilayah_id);
                                    })
                                    ->get()
                                    ->sortBy(function ($kepala) {
                                        return optional($kepala)->wilayah_id;
                                    })
                                    ->map(fn ($kepala) => [
                                        'value' => $kepala->kk_id,
                                        'label' => $kepala->kepalaKeluarga->nik . ' - ' . $kepala->kepalaKeluarga->nama_lengkap . ' - ' . optional($kepala->wilayahs)->wilayah_nama,
                                    ])->pluck('label', 'value')
                            )

                    )
                    ->color('success')
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('tambahanable_ket')
                            ->options(
                                function () {
                                    $kategoris = $this->getOwnerRecord()->kategori;
                                    $modifiedKeyValue = [];
                                    foreach ($kategoris as $kategori) {
                                        $normalizedKey = str_replace(' ', '-', strtolower($kategori));
                                        $modifiedKeyValue[$normalizedKey] = $kategori;
                                    }
                                    return $modifiedKeyValue;
                                }
                            ),
                    ]),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Tidak Valid')
                    ->color('danger')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar tambahan.')
                    ->button(),
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->tambahan_sasaran == 'Keluarga';
    }
}
