<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RtResource\Pages;
use App\Filament\Resources\RtResource\RelationManagers;
use App\Models\Rt;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Factories\Relationship;
use stdClass;

class RtResource extends Resource
{
    protected static ?string $model = Rt::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('rw_id')
                            ->relationship('rw', 'rw_nama')
                            ->required(),
                        TextInput::make('rt_nama')
                            ->autofocus()
                            ->required(),
                        // TextInput::make('rt_ketua'),
                        // Textarea::make('rt_alamat'),
                        // FileUpload::make('rt_profile')
                        //     ->image()
                        //     ->imageEditor()
                        //     ->directory('rt-profiles')
                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) ($rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * ($livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                TextColumn::make('rt_nama')
                    ->label('Rukun Tetangga')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('rw.rw_nama')
                //     ->label('Rukun Warga')
                //     ->searchable()
                //     ->sortable(),
                // ImageColumn::make('rt_profile')
                // TextColumn::make('rt_ketua')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('rt_alamat')
                //     ->toggleable(true),
                // ImageColumn::make('rt_profile')
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListRts::route('/'),
            'create' => Pages\CreateRt::route('/create'),
            'edit' => Pages\EditRt::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return 'Rukun Tetangga';
        } else {
            return 'Neighborhood Association';
        }
    }
}
