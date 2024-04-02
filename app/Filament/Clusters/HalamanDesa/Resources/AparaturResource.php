<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource\RelationManagers;
use App\Models\Desa\Aparatur;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AparaturResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Aparatur::class;

    protected static ?string $navigationIcon = 'fas-user-tie';

    protected static ?string $navigationLabel = 'Pemerintah Desa';

    protected static ?string $slug = 'aparatur';

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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListAparaturs::route('/'),
            'create' => Pages\CreateAparatur::route('/create'),
            'edit' => Pages\EditAparatur::route('/{record}/edit'),
        ];
    }
}
