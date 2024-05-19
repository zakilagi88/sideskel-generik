<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\RelationManagers;

use App\Enums\Kependudukan\StatusHubunganType;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Models\Penduduk;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;

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
            ->recordTitle(
                fn (Penduduk $record): string => "{$record->nama_lengkap} - ({$record->wilayah?->wilayah_nama})"
            )
            ->heading('Anggota Keluarga')
            ->selectable(false)
            ->actions([
                DissociateAction::make()
                    ->label('Pisahkan dari Keluarga')
                    ->color('danger')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar tambahan.')
                    ->button(),
                EditAction::make('edit_kepala')
                    ->label('Ganti Kepala Keluarga')
                    ->color('info')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->hidden(function ($record) {
                        return $record->status_hubungan->value !== 'KEPALA KELUARGA';
                    })
                    ->modalDescription(
                        'Jika mengganti keterangan Kepala Keluarga, maka wajib mengganti salah satu anggota keluarga menjadi Kepala Keluarga.'
                    )
                    ->button()
                    ->using(
                        function ($record, $data) {
                            $record->update([
                                'status_hubungan' => $data['status_hubungan'],
                            ]);
                            Penduduk::find($data['nik'])->update([
                                'status_hubungan' => 'KEPALA KELUARGA',
                            ]);
                            return $record;
                        }
                    )
                    ->form([
                        Select::make('status_hubungan')
                            ->options(
                                fn () => collect(StatusHubunganType::cases())
                                    ->mapWithKeys(fn ($value, $key) => [$value->value => $value->name])
                                    ->filter(fn ($value, $key) => $key !== 'KEPALA KELUARGA')
                            )
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                        Select::make('nik')
                            ->key('dynamic-form')
                            ->options(
                                function () {
                                    return $this->getOwnerRecord()->penduduks()->whereNot('status_hubungan', 'KEPALA KELUARGA')->pluck('nama_lengkap', 'nik');
                                }
                            )
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                    ]),
                EditAction::make('edit_hubungan')
                    ->label('Ganti Hubungan Keluarga')
                    ->color('warning')
                    ->size(ActionSize::ExtraSmall)
                    ->hidden(function ($record) {
                        return $record->status_hubungan->value === 'KEPALA KELUARGA';
                    })
                    ->button()
                    ->form([
                        Select::make('status_hubungan')
                            ->options(StatusHubunganType::class)
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                    ]),
            ], ActionsPosition::BeforeCells)
            ->paginated(false);
    }
}
