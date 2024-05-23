<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources;

use App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource\Pages;
use App\Filament\Clusters\HalamanKependudukan\Resources\DinamikaResource\RelationManagers;
use App\Models\KartuKeluarga;
use App\Models\Dinamika;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Group as FormGroup;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class DinamikaResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Dinamika::class;

    protected static ?string $navigationIcon = 'fas-elevator';

    protected static ?string $navigationLabel = 'Dinamika Penduduk';

    protected static ?string $slug = 'dinamika';

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
                FormGroup::make()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('nik')
                    ->alignJustify()
                    ->color('primary')
                    ->html()
                    ->label('NIK')
                    ->url(fn ($record) => PendudukResource::getUrl('edit', ['record' => $record->nik]))
                    ->openUrlInNewTab()
                    ->sortable(),
                TextColumn::make('penduduk.nama_lengkap')
                    ->label('Nama Lengkap')
                    ->sortable(),
                TextColumn::make('penduduk.kartuKeluargas.kk_id')
                    ->label(
                        fn () => new HtmlString(
                            '<p class="text-sm text-left">No. KK</p> <p class="text-sm text-gray-500 text-left">Kepala Keluarga</p>'
                        )
                    )
                    ->alignLeft()
                    ->html()
                    ->color('primary')
                    ->description(
                        fn ($record) => $record->penduduk->kartuKeluargas?->kepalaKeluarga?->nama_lengkap
                    )
                    ->url(fn ($record) => null)
                    ->placeholder('Kartu Keluarga Tidak Terdata')
                    ->openUrlInNewTab()
                    ->sortable(),
                TextColumn::make('jenis_dinamika')
                    ->label('Jenis Dinamika')
                    ->sortable(),
                TextColumn::make('tanggal_dinamika')
                    ->label('Tanggal Dinamika')
                    ->grow()
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('tanggal_lapor')
                    ->label('Tanggal Lapor')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('catatan_dinamika')
                    ->label('Catatan Dinamika')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions(
                [
                    // Tables\Actions\EditAction::make(),
                ],
                position: ActionsPosition::BeforeColumns
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDinamikas::route('/'),
            'create' => Pages\CreateDinamika::route('/create'),
            'edit' => Pages\EditDinamika::route('/{record}/edit'),
        ];
    }
}
