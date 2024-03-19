<?php

namespace App\Filament\Clusters\Kependudukan\Resources;

use App\Filament\Pages\Deskel\ProfilDeskel;
use App\Filament\Clusters\Kependudukan\Resources\BantuanResource\Pages;
use App\Filament\Clusters\Kependudukan\Resources\BantuanResource\RelationManagers\KeluargasRelationManager;
use App\Filament\Clusters\Kependudukan\Resources\BantuanResource\RelationManagers\PenduduksRelationManager;
use App\Models\Bantuan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
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

class BantuanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Bantuan::class;

    protected static ?string $navigationIcon = 'fas-hand-holding-hand';

    protected static ?string $navigationLabel = 'Data Bantuan';

    protected static ?string $slug = 'bantuan';

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
                TextInput::make('bantuan_program')
                    ->required()
                    ->maxLength(255),
                Select::make('bantuan_sasaran')
                    ->required()
                    ->options([
                        'Penduduk' => 'Penduduk',
                        'Keluarga' => 'Keluarga',
                    ]),
                Textarea::make('bantuan_keterangan')
                    ->autosize()
                    ->required(),
                Cluster::make([
                    DatePicker::make('bantuan_tgl_mulai')
                        ->required()
                        ->postfix('s/d'),
                    DatePicker::make('bantuan_tgl_selesai')
                        ->required(),
                ])->label('Tanggal Bantuan'),
                ToggleButtons::make('bantuan_status')
                    ->options([
                        '0' => 'Tidak Aktif',
                        '1' => 'Aktif',
                    ])
                    ->inline()
                    ->default('1')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bantuan_program')
                    ->label('Program')
                    ->alignJustify()
                    ->wrap()
                    ->description(
                        fn (Bantuan $record) => self::limitwords($record->bantuan_keterangan, 250, ' ...')
                    )
                    ->sortable()
                    ->searchable(),
                TextColumn::make('terdaftar_count')
                    ->label('Terdaftar')
                    ->suffix(
                        fn (Bantuan $record) => $record->bantuan_sasaran == 'Penduduk' ? ' Penduduk' : ' Keluarga'
                    )
                    ->getStateUsing(
                        fn (Bantuan $record) => $record->terdaftar($record->bantuan_sasaran)->count()
                    )
                    ->alignCenter(),
                TextColumn::make('bantuan_tgl_mulai')
                    ->label('Tanggal Berlaku')
                    ->alignJustify()
                    ->getStateUsing(
                        fn (Bantuan $record) => $record->bantuan_tgl_mulai->format('d F Y') . ' - ' . $record->bantuan_tgl_selesai->format('d F Y')
                    )
                    ->sortable(),
                TextColumn::make('bantuan_sasaran')
                    ->label('Sasaran')
                    ->alignJustify()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('bantuan_status')
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
            'index' => Pages\ListBantuans::route('/'),
            'create' => Pages\CreateBantuan::route('/create'),
            'edit' => Pages\EditBantuan::route('/{record}/edit'),
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
