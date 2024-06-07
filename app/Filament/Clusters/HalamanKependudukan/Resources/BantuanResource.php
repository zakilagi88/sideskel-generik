<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources;

use App\Filament\Pages\Deskel\ProfilDeskel;
use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\Pages;
use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\RelationManagers\KeluargasRelationManager;
use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\RelationManagers\PenduduksRelationManager;
use App\Models\Bantuan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\{DatePicker, Group, Select, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Filament\Tables\Table;
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
                Group::make([
                    DatePicker::make('bantuan_tgl_mulai')
                        ->required()
                        ->postfix('s/d'),
                    DatePicker::make('bantuan_tgl_selesai')
                        ->required(),
                ])->columns(2)->label('Tanggal Bantuan'),
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
                        fn (Bantuan $record) => Carbon::parse($record->bantuan_tgl_mulai)->isoFormat('dddd, D MMMM YYYY') . ' - ' . Carbon::parse($record->bantuan_tgl_selesai)->isoFormat('dddd, D MMMM YYYY')
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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()->iconSize(IconSize::Small)->color('success')->modalWidth(MaxWidth::FiveExtraLarge),
                    Tables\Actions\EditAction::make()->iconSize(IconSize::Small)->color('primary'),
                    Tables\Actions\DeleteAction::make()->iconSize(IconSize::Small)->color('danger'),
                ])->icon("fas-gears")->iconPosition('after')->color('success')->button()->label('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Detail Bantuan')
                            ->schema([
                                TextEntry::make('bantuan_program')
                                    ->label('Program'),
                                TextEntry::make('bantuan_sasaran')
                                    ->label('Sasaran'),
                                TextEntry::make('bantuan_keterangan')
                                    ->label('Keterangan'),
                                TextEntry::make('bantuan_tgl_mulai')
                                    ->date('d F Y')
                                    ->label('Tanggal Berlaku'),
                                TextEntry::make('bantuan_tgl_selesai')
                                    ->date('d F Y')
                                    ->label('Tanggal Selesai'),
                                IconEntry::make('bantuan_status')
                                    ->boolean()
                            ]),
                        Tab::make('Data Terdaftar')
                            ->schema(
                                fn (Bantuan $record) => match ($record->bantuan_sasaran) {
                                    'Penduduk' => [
                                        RepeatableEntry::make('penduduks')
                                            ->label('Data Penduduk Terdaftar')
                                            ->grid(2)
                                            ->schema([
                                                TextEntry::make('nik')->label('NIK')->url(fn ($record) => PendudukResource::getUrl('view', ['record' => $record->nik]))->color('primary')->inlineLabel(),
                                                TextEntry::make('nama_lengkap')->label('Nama Lengkap')->placeholder('Belum Diiisi')->inlineLabel(),
                                                TextEntry::make('wilayah.wilayah_nama')->label('Wilayah')->placeholder('Belum Diiisi')->inlineLabel(),
                                                TextEntry::make('jenis_kelamin')->label('Jenis Kelamin')->placeholder('Belum Diiisi')->inlineLabel(),
                                                TextEntry::make('umur')->label('Usia')->suffix(' Tahun')->inlineLabel(),
                                            ])

                                    ],
                                    'Keluarga' => [
                                        RepeatableEntry::make('keluargas')
                                            ->label('Data Keluarga Terdaftar')
                                            ->grid(2)
                                            ->schema([
                                                TextEntry::make('kepalaKeluarga.nik')->label('NIK'),
                                                TextEntry::make('kepalaKeluarga.nama_lengkap')->label('Nama Kepala Keluarga'),
                                                TextEntry::make('wilayah.wilayah_nama')->label('Wilayah'),
                                                TextEntry::make('kepalaKeluarga.jenis_kelamin')->label('Jenis Kelamin'),
                                                TextEntry::make('kepalaKeluarga.umur')->label('Usia')->suffix(' Tahun')
                                            ])
                                    ]
                                }
                            )

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
