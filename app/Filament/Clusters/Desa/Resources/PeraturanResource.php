<?php

namespace App\Filament\Clusters\Desa\Resources;

use App\Filament\Clusters\Desa\HalamanDesa;
use App\Filament\Clusters\Desa\Resources\PeraturanResource\Pages;
use App\Models\Desa\Peraturan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeraturanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Peraturan::class;

    protected static ?string $navigationIcon = 'fas-book';

    protected static ?string $navigationLabel = 'Buku Peraturan Desa';

    protected static ?string $slug = 'peraturan';

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
            'index' => Pages\ListPeraturans::route('/'),
            'create' => Pages\CreatePeraturan::route('/create'),
            'edit' => Pages\EditPeraturan::route('/{record}/edit'),
        ];
    }
}
