<?php

namespace App\Filament\Clusters\Desa\Resources;

use App\Filament\Clusters\Desa\HalamanDesa;
use App\Filament\Clusters\Desa\Resources\KeputusanResource\Pages;
use App\Filament\Clusters\Desa\Resources\KeputusanResource\RelationManagers;
use App\Models\Desa\Keputusan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeputusanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Keputusan::class;

    protected static ?string $navigationIcon = 'fas-gavel';

    protected static ?string $navigationLabel = 'Buku Keputusan Kepala Desa';

    protected static ?string $slug = 'keputusan';

    protected static ?string $cluster = HalamanDesa::class;

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
                Forms\Components\TextInput::make('kep_nomor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('kep_tanggal')
                    ->required(),
                Forms\Components\TextInput::make('kep_tentang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('kep_uraian_singkat')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('kep_no_dilaporkan')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('kep_tgl_dilaporkan'),
                Forms\Components\TextInput::make('kep_keterangan')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kep_nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kep_tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kep_tentang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kep_no_dilaporkan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kep_tgl_dilaporkan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kep_keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeputusans::route('/'),
            'create' => Pages\CreateKeputusan::route('/create'),
            'edit' => Pages\EditKeputusan::route('/{record}/edit'),
        ];
    }
}
