<?php

namespace App\Filament\Clusters\HalamanStatistik\Resources;

use App\Filament\Clusters\HalamanStatistik;
use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource\Pages;
use App\Models\StatSDM;
use App\Services\GenerateEnumUnionQuery;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StatSDMResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = StatSDM::class;

    protected static ?string $cluster = HalamanStatistik::class;

    protected static ?string $navigationIcon = '';

    protected static ?string $navigationLabel = 'Statistik';

    protected static ?string $slug = 'kependudukan';

    protected static ?string $breadcrumb = 'Kependudukan';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static bool $shouldRegisterNavigation = false;


    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\Select::make('key')
                    ->options(GenerateEnumUnionQuery::getEnumOptions())
                    ->label('Key'),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(StatSDM::class, 'slug', ignoreRecord: true),
                Forms\Components\Select::make('stat_kategori_id')
                    ->relationship('kat', 'nama')
                    ->label('Kategori Statistik'),
                Forms\Components\TextInput::make('deskripsi')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatSDMs::route('/'),
            'edit' => Pages\EditStatSDM::route('/{record}'),
        ];
    }
}
