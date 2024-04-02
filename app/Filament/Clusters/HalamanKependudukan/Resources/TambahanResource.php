<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources;

use App\Filament\Pages\Deskel\ProfilDeskel;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\Pages;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers\KeluargasRelationManager;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers\PenduduksRelationManager;
use App\Models\Tambahan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Coolsam\FilamentFlatpickr\Enums\FlatpickrTheme;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Components\{DatePicker, Select, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class TambahanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Tambahan::class;

    protected static ?string $navigationIcon = 'fas-hand-holding-hand';

    protected static ?string $navigationLabel = 'Data Tambahan';

    protected static ?string $slug = 'tambahan';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tambahan_nama')
                    ->required()
                    ->label('Nama Data Tambahan')
                    ->disabled((auth()->user()->hasRole('Operator Wilayah') || auth()->user()->hasRole('Monitor Wilayah')) ? true : false)
                    ->maxLength(255),
                Select::make('tambahan_sasaran')
                    ->required()
                    ->disabled((auth()->user()->hasRole('Operator Wilayah') || auth()->user()->hasRole('Monitor Wilayah')) ? true : false)

                    ->label('Sasaran Data Tambahan')
                    ->options([
                        'Penduduk' => 'Penduduk',
                        'Keluarga' => 'Keluarga',
                    ]),
                Textarea::make('tambahan_keterangan')
                    ->label('Keterangan Data Tambahan')
                    ->disabled((auth()->user()->hasRole('Operator Wilayah') || auth()->user()->hasRole('Monitor Wilayah')) ? true : false)

                    ->autosize()
                    ->required(),
                Cluster::make([
                    Flatpickr::make('tambahan_tgl_mulai')
                        ->label('Tanggal Mulai')
                        ->disabled((auth()->user()->hasRole('Operator Wilayah') || auth()->user()->hasRole('Monitor Wilayah')) ? true : false)

                        ->placeholder('Pilih Tanggal Mulai')
                        ->animate()
                        ->enableSeconds(false) // Enable seconds in a time picker

                        ->allowInput(true)
                        ->clickOpens(true)
                        ->theme(FlatpickrTheme::MATERIAL_BLUE)
                        ->postfix('s/d')
                        ->required(),
                    Flatpickr::make('tambahan_tgl_selesai')
                        ->label('Tanggal Selesai')
                        ->disabled((auth()->user()->hasRole('Operator Wilayah') || auth()->user()->hasRole('Monitor Wilayah')) ? true : false)

                        ->placeholder('Pilih Tanggal Selesai')
                        ->animate()
                        ->enableSeconds(false) // Enable seconds in a time picker
                        ->allowInput(true)
                        ->clickOpens(true)
                        ->theme(FlatpickrTheme::MATERIAL_BLUE)
                        ->required(),
                ])->label('Tanggal Berlaku'),
                ToggleButtons::make('tambahan_status')
                    ->label('Status Data Tambahan')
                    ->options([
                        '0' => 'Tidak Aktif',
                        '1' => 'Aktif',
                    ])
                    ->inline()
                    ->disabled((auth()->user()->hasRole('Operator Wilayah') || auth()->user()->hasRole('Monitor Wilayah')) ? true : false)

                    ->default('1')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tambahan_nama')
                    ->label('Nama Tambahan')
                    ->alignJustify()
                    ->wrap()
                    ->description(
                        fn (Tambahan $record) => self::limitwords($record->tambahan_keterangan, 250, ' ...')
                    )
                    ->sortable()
                    ->searchable(),
                TextColumn::make('terdaftar_count')
                    ->label('Terdaftar')
                    ->suffix(
                        fn (Tambahan $record) => $record->tambahan_sasaran == 'Penduduk' ? ' Penduduk' : ' Keluarga'
                    )
                    ->getStateUsing(
                        fn (Tambahan $record) => $record->terdaftar($record->tambahan_sasaran)->count()
                    )
                    ->alignCenter(),
                TextColumn::make('tambahan_tgl_mulai')
                    ->label('Tanggal Berlaku')
                    ->alignJustify()
                    ->getStateUsing(
                        fn (Tambahan $record) => Carbon::parse($record->tambahan_tgl_mulai)->isoFormat('dddd, D MMMM YYYY') . ' - ' . Carbon::parse($record->tambahan_tgl_selesai)->isoFormat('dddd, D MMMM YYYY')
                    )
                    ->sortable(),
                TextColumn::make('tambahan_sasaran')
                    ->label('Sasaran')
                    ->alignJustify()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('tambahan_status')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PenduduksRelationManager::class,
            KeluargasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTambahans::route('/'),
            'create' => Pages\CreateTambahan::route('/create'),
            'edit' => Pages\EditTambahan::route('/{record}/edit'),
        ];
    }

    public static function limitwords($value, $limit = 100, $end = '...')
    {
        if (Str::length($value) <= $limit) {
            return $value;
        }

        return Str::limit($value, $limit, $end);
    }
}
