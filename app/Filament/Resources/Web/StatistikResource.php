<?php

namespace App\Filament\Resources\Web;

use App\Filament\Pages\PendudukStats;
use App\Filament\Resources\Web\StatistikResource\Pages;
use App\Filament\Resources\Web\StatistikResource\RelationManagers;
use App\Models\Statistik;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatistikResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Statistik::class;

    protected static ?string $navigationIcon = 'fas-people-group';

    protected static ?string $navigationLabel = 'Statistik';

    protected static ?string $slug = 'web-statistik';

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
                Forms\Components\TextInput::make('stat_heading')
                    ->required()

                    ->label('Heading')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('stat_slug', Str::slug($state))),

                // ->afterStateUpdated(
                //     function (string $operation, $state, Set $set) {
                //         if ($operation === 'create') {
                //             $set('judul', Str::title($state));
                //             $set('slug', Str::slug($state));
                //         }
                //     }
                // ),
                Forms\Components\TextInput::make('stat_slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Statistik::class, 'stat_slug', ignoreRecord: true),
                Forms\Components\TextInput::make('stat_subheading')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stat_deskripsi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stat_grafik_path')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stat_tabel_path')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stat_grafik_jenis')
                    ->maxLength(255),
                Forms\Components\Toggle::make('stat_tampil')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stat_heading')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stat_subheading')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stat_slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stat_deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stat_grafik_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stat_tabel_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stat_grafik_jenis')
                    ->searchable(),
                Tables\Columns\IconColumn::make('stat_tampil')
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
            'index' => Pages\ManageStatistiks::route('/'),

        ];
    }
}
