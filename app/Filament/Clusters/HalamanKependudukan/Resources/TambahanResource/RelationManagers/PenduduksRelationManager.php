<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers;

use App\Filament\Exports\TambahanExporter;
use App\Models\Penduduk;
use App\Models\Tambahan;
use App\Models\Tambahanable;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PenduduksRelationManager extends RelationManager
{
    protected static string $relationship = 'penduduks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $operatorWilayah = Filament::auth()->user()->hasRole('Operator Wilayah');
        $monitorWilayah = Filament::auth()->user()->hasRole('Monitor Wilayah');
        return $table
            ->recordTitleAttribute('nama_lengkap')
            ->heading('Data Penduduk Terdaftar Data Tambahan')
            ->columns([
                TextColumn::make('no')->label('No')->alignCenter()->rowIndex(),
                TextColumn::make('tambahanable_ket')->label('Keterangan')->badge(),
                TextColumn::make('nik')->label('NIK'),
                TextColumn::make('nama_lengkap')->label('Nama'),
                TextColumn::make('alamat_sekarang')->label('Alamat'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('tanggal_lahir')
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
                                    ->where('tambahanable_type', Penduduk::class)
                                    ->where('tambahanable_id', $value)
                                    ->exists()
                            )
                            ->searchable()
                            ->placeholder('Cari NIK atau Nama Lengkap...')
                            ->options(
                                fn () => Penduduk::query()
                                    ->with('kartuKeluarga.wilayahs')
                                    ->when($operatorWilayah, function ($query) {
                                        $query->whereHas('kartuKeluarga', function ($query) {
                                            $query->where('wilayah_id', auth()->user()->wilayah_id);
                                        });
                                    })
                                    ->when($monitorWilayah, function ($query) {
                                        $query->whereHas('kartuKeluarga', function ($query) {
                                            $query->where('wilayah_id', auth()->user()->wilayah_id);
                                        });
                                    })
                                    ->get()
                                    ->sortBy(function ($penduduk) {
                                        return optional($penduduk->kartuKeluarga)->wilayah_id;
                                    })
                                    ->map(fn ($penduduk) => [
                                        'value' => $penduduk->nik,
                                        'label' => $penduduk->nik . ' - ' . $penduduk->nama_lengkap . ' - ' . optional($penduduk->kartuKeluarga->wilayahs)->wilayah_nama,
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
        return $ownerRecord->tambahan_sasaran == 'Penduduk';
    }
}
