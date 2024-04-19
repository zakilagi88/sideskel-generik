<?php

namespace App\Filament\Resources\Web;

use App\Filament\Clusters\HalamanStatistik;
use App\Filament\Pages\PendudukStats;
use App\Filament\Resources\Web\StatResource\Pages;
use App\Filament\Resources\Web\StatResource\RelationManagers;
use App\Models\Stat;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Clusters\Cluster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class StatResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Stat::class;

    protected static ?string $cluster = HalamanStatistik::class;

    protected static ?string $navigationIcon = 'fas-people-group';

    protected static ?string $navigationLabel = 'Statistik';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'web-stat';

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
                    ->readOnly()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Stat::class, 'slug', ignoreRecord: true),
                Forms\Components\Select::make('stat_kategori_id')
                    ->relationship('kat', 'nama')
                    ->label('Kategori Statistik'),
                Forms\Components\TextInput::make('deskripsi')
                    ->maxLength(255),
                Forms\Components\Toggle::make('tampil')
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
                // Tables\Columns\TextColumn::make('stat_komponen')
                //     ->getStateUsing(
                //         function (Stat $record) {
                //             // Mengonversi array ke dalam koleksi Laravel
                //             $subheadings = array_column($record->stat_komponen, 'stat_subheading');

                //             return ($subheadings);
                //         }
                //     )
                //     ->listWithLineBreaks()
                //     ->bulleted(),
                //     ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('tampil')
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
            'index' => Pages\ListStats::route('/'),
            'edit' => Pages\EditStat::route('/{record}'),

        ];
    }
}
