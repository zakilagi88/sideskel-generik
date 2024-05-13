<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers;

use App\Filament\Exports\TambahanExporter;
use App\Models\Penduduk;
use App\Models\Tambahan;
use App\Models\Tambahanable;
use App\Models\Wilayah;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;
        return $table
            ->recordTitle(
                fn (Penduduk $record): string => "{$record->nama_lengkap} - ({$record->wilayah?->wilayah_nama})"
            )
            ->modifyQueryUsing(
                fn (Builder $query) => $query->byWilayah($authUser, $descendants)
            )
            ->heading('Data Penduduk Terdaftar Data Tambahan')
            ->columns([
                TextColumn::make('no')->label('No')->alignCenter()->rowIndex(),
                TextColumn::make('tambahanable_ket')->label('Keterangan')->badge()->sortable()->alignJustify(),
                TextColumn::make('nik')->label('NIK'),
                TextColumn::make('wilayah.wilayah_nama')->label('Wilayah'),
                TextColumn::make('nama_lengkap')->label('Nama'),
                TextColumn::make('alamat_sekarang')->label('Alamat'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('umur')
                    ->sortable()
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
                        fn (Builder $query) => $query->byWilayah($authUser, $descendants)
                    )
                    ->recordSelectSearchColumns(['nama_lengkap', 'nik'])
                    ->preloadRecordSelect()

                    ->form(fn (AttachAction $action): array => [
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
                        $action->getRecordSelect()->multiple(),
                    ])
                    ->color('success')
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
                ]),
            ]);
    }


    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->sasaran == 'Penduduk';
    }
}