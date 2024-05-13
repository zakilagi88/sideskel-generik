<?php

namespace App\Filament\Clusters\HalamanBerita\Resources\KategoriBeritaResource\RelationManagers;

use App\Models\Web\Berita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeritasRelationManager extends RelationManager
{
    protected static string $relationship = 'beritas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->description(
                        fn (Berita $record) => $record->meta_description,

                    )

                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->icon('fas-folder')
                    ->badge()
                    ->colors([
                        'primary' => 'blue',
                        'success' => 'green',
                        'warning' => 'yellow',
                        'danger' => 'red',
                        'info' => 'indigo',
                        'gray' => 'gray',
                    ])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('scheduled_for')
                    ->label('Dijadwalkan pada')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default(''),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasikan pada')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
