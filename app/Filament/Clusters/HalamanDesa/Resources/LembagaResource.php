<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\RelationManagers;
use App\Models\Lembaga;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Route;

class LembagaResource extends Resource
{
    protected static ?string $model = Lembaga::class;

    protected static ?string $navigationIcon = 'fas-users-line';

    protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'lembaga';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Masukkan Nama Lembaga')
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        function (string $operation, $state, Set $set) {
                            if (($operation === 'create') && ($operation === 'edit')) {
                                $set('nama', Str::title($state));
                                $set('slug', Str::slug($state));
                            }
                        }
                    ),
                Forms\Components\TextInput::make('singkatan')
                    ->required()
                    ->placeholder('Masukkan Singkatan Lembaga')
                    ->maxLength(20),
                Forms\Components\Textarea::make('deskripsi')
                    ->nullable()
                    ->placeholder('Masukkan Deskripsi Lembaga')
                    ->autosize(),
                Forms\Components\Hidden::make('slug')
                    ->dehydrated()
                    ->required(
                        fn (Get $get, Set $set) => $set(
                            'slug',
                            Str::slug($get('nama'))
                        )
                    )
                    ->unique(Lembaga::class, 'slug', ignoreRecord: true),

                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->placeholder('Masukkan Alamat Lembaga')
                    ->autosize(),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('dokumen_id')
                            ->placeholder('Pilih Dokumen Dasar Hukum Pembentukan Lembaga')
                            ->relationship('dokumen', 'dok_nama'),
                        Forms\Components\FileUpload::make('logo_url')
                            ->label('Logo Lembaga')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('deskel/lembaga')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->imagePreviewHeight('250')
                            ->loadingIndicatorPosition('right')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left'),
                    ]),

                Forms\Components\Repeater::make('kategori_jabatan')
                    ->label('Kategori Jabatan')
                    ->defaultItems(1)
                    ->simple(
                        TextInput::make('jabatan')
                            ->label('Nama Jabatan')
                            ->placeholder('Masukkan Nama Kategori Jabatan Lembaga')
                            ->maxLength(255),
                    ),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->alignLeft()
                    ->searchable(),
                Tables\Columns\TextColumn::make('singkatan')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('anggota_count')
                    ->searchable()
                    ->alignCenter()
                    ->label('Jumlah Anggota')
                    ->suffix(' Orang')
                    ->counts('anggota'),
                Tables\Columns\TextColumn::make('alamat')
                    ->alignJustify()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dokumen.dok_nama')
                    ->alignJustify()
                    ->sortable(),
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
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnggotaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLembagas::route('/'),
            'create' => Pages\CreateLembaga::route('/create'),
            'edit' => Pages\EditLembaga::route('/{record}/edit'),
        ];
    }
}