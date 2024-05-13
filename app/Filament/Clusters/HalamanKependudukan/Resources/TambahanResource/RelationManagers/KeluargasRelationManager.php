<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers;

use App\Filament\Exports\TambahanExporter;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Tambahanable;
use App\Models\Wilayah;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\{AttachAction, BulkAction, BulkActionGroup, DetachAction, DetachBulkAction, EditAction, ExportAction};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

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
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->byWilayah($authUser, $descendants)
            )
            ->recordTitle(
                fn (KartuKeluarga $record): string => "{$record->kepalaKeluarga?->nama_lengkap} - ({$record->kepalaKeluarga->wilayah->wilayah_nama})"
            )
            ->heading('Data Keluarga Terdaftar Data Tambahan')
            ->columns([
                TextColumn::make('no')->label('No')->alignCenter()->rowIndex(),
                TextColumn::make('tambahanable_ket')->label('Keterangan')->badge()->sortable()->alignJustify(),
                TextColumn::make('kepalaKeluarga.nama_lengkap')->label('Nama Kepala Keluarga'),
                TextColumn::make('kepalaKeluarga.nik')->label('NIK'),
                TextColumn::make('wilayah.wilayah_nama')->label('Wilayah'),
                TextColumn::make('kepalaKeluarga.alamat_sekarang')->label('Alamat'),
                TextColumn::make('kepalaKeluarga.jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('kepalaKeluarga.tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('kepalaKeluarga.umur')->sortable()->label('Usia')->suffix(' Tahun')
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
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn (array $filters): array => [
                Group::make()
                    ->extraAttributes(['class' => 'mb-4'])
                    ->schema([
                        $filters['tambahanable_ket'],
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
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

                    ->recordSelectOptionsQuery(
                        fn (Builder $query) => $query
                            ->with(['kepalaKeluarga', 'wilayah', 'kepalaKeluarga.wilayah'])
                            ->byWilayah($authUser, $descendants)
                            ->leftJoin('penduduk as p', 'kartu_keluarga.kk_id', '=', 'p.kk_id')
                            ->where('p.status_hubungan', 'KEPALA KELUARGA')
                    )
                    ->recordSelectSearchColumns(['p.nama_lengkap', 'p.nik'])
                    ->preloadRecordSelect()
                    ->color('success')
                    ->form(fn (AttachAction $action): array => [
                        Forms\Components\Select::make('tambahanable_ket')
                            ->required()
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
                            )
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                        $action->getRecordSelect()->multiple(),
                    ])
            ])
            ->actions([
                DetachAction::make()
                    ->label('Tidak Valid')
                    ->color('danger')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar tambahan.')
                    ->hidden(fn () => $authUser->hasRole('Monitor Wilayah'))
                    ->button(),
                EditAction::make('edit')
                    ->label('Ganti Keterangan')
                    ->color('info')
                    ->size(ActionSize::ExtraSmall)
                    ->button()
                    ->form([
                        Forms\Components\Select::make('tambahanable_ket')
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
                            )
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                    ]),
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    BulkAction::make('edit')
                        ->icon('fas-edit')
                        ->iconSize(IconSize::Small)
                        ->form([
                            Forms\Components\Select::make('tambahanable_ket')
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
                                )
                                ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),

                        ])
                        ->action(function (Collection $records, array $data) {

                            $records->each(function ($penduduk) use ($data) {
                                $penduduk->tambahans()->updateExistingPivot($penduduk->id, [
                                    'tambahanable_ket' => $data['tambahanable_ket'],
                                ]);
                            });
                            Notification::make()
                                ->title('Keterangan Berhasil Diubah')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion()
                ])->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->sasaran == 'Keluarga';
    }
}
