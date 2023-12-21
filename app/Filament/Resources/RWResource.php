<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RWResource\Pages;
use App\Filament\Resources\RWResource\RelationManagers;
use App\Models\RT;
use App\Models\RW;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class RWResource extends Resource
{
    protected static ?string $model = RW::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Wilayah';

    protected static ?string $pluralModelLabel = 'Rukun Warga';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('rw_nama'),
                        // Select::make('rt_nama')->options(RW::with('rt_group')->get()
                        //     ->flatMap(function ($rw) {
                        //         return $rw->rt_group->pluck('rt_nama', 'rt_id');
                        //     }))
                        //     ->multiple(),
                        Select::make('rts')
                            ->multiple()
                            ->relationship('rt_group', 'rt_nama')
                        // ->label('RT')
                        // ->getSearchResultsUsing(fn (string $search): array => RT::where('rt_nama', 'like', "%{$search}%")->limit(50)->pluck('rt_nama', 'rt_id')->toArray())
                        // ->getOptionLabelsUsing(fn (array $values): array => RT::whereIn('rt_id', $values)->pluck('rt_nama', 'rt_id')->toArray()),

                    ])->columnStart(1)
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
                TextColumn::make('rw_nama')
                    ->Label('Nama Rukun Warga')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rt_group.rt_id')
                    ->label('RT')
                    // ->options(
                    //     RT::pluck('rt_id')->toArray()
                    // )
                    ->searchable(),

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
            'index' => Pages\ListRWS::route('/'),
            'create' => Pages\CreateRW::route('/create'),
            'edit' => Pages\EditRW::route('/{record}/edit'),
        ];
    }
}
