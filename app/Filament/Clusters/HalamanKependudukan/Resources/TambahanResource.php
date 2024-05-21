<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources;

use App\Filament\Pages\Deskel\ProfilDeskel;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\Pages;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers\KeluargasRelationManager;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource\RelationManagers\PenduduksRelationManager;
use App\Models\Tambahan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\{DatePicker, Group, Repeater, Select, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Filament\Tables\Table;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

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
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();

        $authWilayah = $authUser->hasRole('Operator Wilayah') || $authUser->hasRole('Monitor Wilayah') ? true : false;


        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->label('Nama Data Tambahan')
                    ->live(onBlur: true)
                    ->hidden($authWilayah)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                TextInput::make('slug')
                    ->disabled()
                    ->hidden($authWilayah)
                    ->label('Rute Data Tambahan')
                    ->dehydrated()
                    ->required()
                    ->unique(Tambahan::class, 'slug', ignoreRecord: true),
                Textarea::make('keterangan')
                    ->label('Keterangan Data Tambahan')
                    ->hidden($authWilayah)
                    ->autosize()
                    ->required(),
                Select::make('sasaran')
                    ->required()
                    ->hidden($authWilayah)
                    ->disabledOn('edit')
                    ->label('Sasaran Data Tambahan')
                    ->options([
                        'Penduduk' => 'Penduduk',
                        'Keluarga' => 'Keluarga',
                    ]),
                Group::make()
                    ->schema([
                        Group::make([
                            DatePicker::make('tgl_mulai')
                                ->label('Tanggal Mulai')
                                ->hidden($authWilayah)
                                ->placeholder('Pilih Tanggal Mulai')
                                ->required(),
                            DatePicker::make('tgl_selesai')
                                ->label('Tanggal Selesai')
                                ->hidden($authWilayah)
                                ->placeholder('Pilih Tanggal Selesai')
                                ->required(),
                        ])->label('Tanggal Berlaku'),
                        ToggleButtons::make('status')
                            ->label('Status Data Tambahan')
                            ->options([
                                '0' => 'Tidak Aktif',
                                '1' => 'Aktif',
                            ])
                            ->inline()
                            ->hidden($authWilayah)
                            ->default('1')
                            ->required(),
                    ]),
                Repeater::make('kategori')
                    ->label('Kategori Data Tambahan')
                    ->hidden($authWilayah)
                    ->simple(
                        TextInput::make('kategori_nama')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255),
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Tambahan')
                    ->alignJustify()
                    ->wrap()
                    ->description(
                        fn (Tambahan $record) => self::limitwords($record->keterangan, 250, ' ...')
                    )
                    ->sortable()
                    ->searchable(),
                TextColumn::make('terdaftar_count')
                    ->label('Terdaftar')
                    ->suffix(
                        fn (Tambahan $record) => $record->sasaran == 'Penduduk' ? ' Penduduk' : ' Keluarga'
                    )
                    ->getStateUsing(
                        fn (Tambahan $record) => $record->terdaftar($record->sasaran)->count()
                    )
                    ->alignCenter(),
                TextColumn::make('tgl_mulai')
                    ->label('Tanggal Berlaku')
                    ->alignJustify()
                    ->getStateUsing(
                        fn (Tambahan $record) => Carbon::parse($record->tgl_mulai)->isoFormat('dddd, D MMMM YYYY') . ' - ' . Carbon::parse($record->tgl_selesai)->isoFormat('dddd, D MMMM YYYY')
                    )
                    ->sortable(),
                TextColumn::make('sasaran')
                    ->label('Sasaran')
                    ->alignJustify()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('status')
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
                Tables\Actions\EditAction::make()->button(),
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