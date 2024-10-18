<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\RelationManagers;

use App\Enums\Kependudukan\StatusHubunganType;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Filament\Exports\PendudukExporter;
use App\Models\Penduduk;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PenduduksRelationManager extends RelationManager
{
    protected static string $relationship = 'penduduks';

    protected static ?string $title = 'Anggota Keluarga';

    public function form(Form $form): Form
    {
        return PendudukResource::form($form);
    }


    public function table(Table $table): Table
    {
        return PendudukResource::table($table)
            ->columns(
                [
                    TextColumn::make('kk_id')
                        ->html()
                        ->color('primary')
                        ->label(
                            fn() => new HtmlString(
                                '<p class="text-sm text-left">No. KK</p> <p class="text-sm text-gray-500 text-left">Kepala Keluarga</p>'
                            )
                        )
                        ->searchable()
                        ->url(fn($record) => KartuKeluargaResource::getUrl('edit', ['record' => $record->kk_id]))

                        ->description(fn(Penduduk $record) => ($record->kartuKeluargas?->kepalaKeluarga?->nama_lengkap) ?: 'Tidak Diketahui'),
                    TextColumn::make('nik')
                        ->color('primary')
                        ->label(
                            fn() => new HtmlString(
                                '<p class="text-sm text-left">NIK</p> <p class="text-sm text-gray-500 text-left">Nama Lengkap</p>'
                            )
                        )
                        ->url(fn($record) => PendudukResource::getUrl('edit', ['record' => $record->nik]))
                        ->description(fn(Penduduk $record) => ($record->nama_lengkap) ?: 'Tidak Diketahui')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('wilayah.wilayah_nama')
                        ->placeholder('Wilayah Tidak Diketahui')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('jenis_kelamin')
                        ->searchable()
                        ->toggleable()
                        ->sortable(),
                    TextColumn::make('tempat_lahir')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('tanggal_lahir')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('agama')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('pendidikan')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('status_perkawinan')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('status_hubungan')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                    TextColumn::make('pekerjaan')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('alamat_sekarang')
                        ->searchable()
                        ->toggleable()
                        ->sortable(),
                    TextColumn::make('alamat_sebelumnya')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('status_dasar')
                        ->searchable()
                        ->badge()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                    TextColumn::make('status_pengajuan')
                        ->searchable()
                        ->badge()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->sortable(),
                ]
            )

            ->recordTitle(fn(Penduduk $record): string => "{$record->nama_lengkap} - ({$record->wilayah?->wilayah_nama})")
            ->heading('Anggota Keluarga')
            ->selectable(false)
            ->headerActions([
                ExportAction::make()
                    ->exporter(PendudukExporter::class)
                    ->color('primary')
                    ->label('Ekspor Data')
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->columnMapping(),
                Action::make('penduduk')->label('Tambah Anggota')
                    ->url(route('filament.panel.kependudukan.resources.keluarga.create')),
            ])
            ->actions([
                DissociateAction::make()
                    ->label('Pisahkan dari Keluarga')
                    ->color('danger')
                    ->size(ActionSize::Small)
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar tambahan.')
                    ->hidden(fn($record) => (($record->status_hubungan->value === 'KEPALA KELUARGA') || ($this->getOwnerRecord()->penduduks->count() <= 1)))
                    ->button(),
                EditAction::make('edit_kepala')
                    ->label('Ganti Kepala Keluarga')
                    ->color('info')
                    ->size(ActionSize::Small)
                    ->requiresConfirmation()
                    ->hidden(fn($record) => (($record->status_hubungan->value !== 'KEPALA KELUARGA') || ($this->getOwnerRecord()->penduduks->count() <= 1)))
                    ->modalDescription('Jika mengganti keterangan Kepala Keluarga, maka wajib mengganti salah satu anggota keluarga menjadi Kepala Keluarga.')
                    ->button()
                    ->using(
                        function ($record, $data) {
                            //update data untuk Kepala Lama
                            $record->update(['status_hubungan' => $data['status_hubungan']]);
                            //update data untuk Kepala Baru
                            Penduduk::find($data['nik'])->update(['status_hubungan' => 'KEPALA KELUARGA']);
                            return $record;
                        }
                    )
                    ->form([
                        Select::make('status_hubungan')
                            ->options(
                                fn() => collect(StatusHubunganType::cases())
                                    ->mapWithKeys(fn($value, $key) => [$value->value => $value->name])
                                    ->filter(fn($value, $key) => $key !== 'KEPALA KELUARGA')
                            )
                            ->dehydrateStateUsing(fn(string $state): string => ucwords($state)),
                        Select::make('nik')
                            ->key('dynamic-form')
                            ->options(fn() => $this->getOwnerRecord()->penduduks()->whereNot('status_hubungan', 'KEPALA KELUARGA')->pluck('nama_lengkap', 'nik'))
                            ->dehydrateStateUsing(fn(string $state): string => ucwords($state)),
                    ]),
                EditAction::make('edit_hubungan')
                    ->label('Ganti Hubungan Keluarga')
                    ->color('warning')
                    ->size(ActionSize::Small)
                    ->hidden(fn($record) => $record->status_hubungan->value === 'KEPALA KELUARGA')
                    ->button()
                    ->form([
                        Select::make('status_hubungan')
                            ->options(
                                fn() => collect(StatusHubunganType::cases())
                                    ->mapWithKeys(fn($value, $key) => [$value->value => $value->name])
                                    ->filter(fn($value, $key) => $key !== 'KEPALA KELUARGA')
                            )
                            ->dehydrateStateUsing(fn(string $state): string => ucwords($state)),
                    ]),
            ], ActionsPosition::AfterCells)
            ->paginated(false);
    }
}
