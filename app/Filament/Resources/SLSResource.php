<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SLSResource\Pages;
use App\Filament\Resources\SLSResource\RelationManagers;
use App\Models\Kelurahan;
use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SLSResource extends Resource
{
    protected static ?string $model = SLS::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('sls_kode')
                    ->label('Kode SLS')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('kel_id')
                    ->label('Kelurahan')
                    ->relationship('kel_groups', 'kel_nama')
                    ->options(function () {
                        return Kelurahan::all()->pluck('kel_nama', 'kel_id');
                    })
                    ->live(),

                TextInput::make('sls_nama')
                    ->label('Nama SLS')
                    ->required(
                        function (Get $get, Set $set) {
                            $rw_id = $get('rw_id');
                            $rt_id = $get('rt_id');

                            if ($rw_id && $rt_id) {
                                $rw_nama = RW::find($rw_id)->rw_nama;
                                $rt_nama = RT::find($rt_id)->rt_nama;

                                $set('sls_nama', "{$rt_nama}/{$rw_nama}");
                            }

                            return true;
                        }
                    )
                    ->autofocus(),
                Select::make('rw_id')
                    ->label('RW')
                    ->options(function () {
                        return RW::all()->pluck('rw_nama', 'rw_id');
                    })
                    ->live(),
                Select::make('rt_id')
                    ->label('RT')
                    ->options(function () {
                        return RT::all()->pluck('rt_nama', 'rt_id');
                    })
                    ->live(),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('sls_kode')
                    ->label('Kode SLS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sls_nama')
                    ->label('Nama SLS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rw_groups.rw_nama')
                    ->label('Nama RW')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rt_groups.rt_nama')
                    ->label('Nama RT')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('rw_id')
                    ->label('RW')
                    ->options(
                        function () {
                            return SLS::with('rw_groups')->get()->pluck('rw_groups.rw_nama', 'rw_groups.rw_id');
                        }
                    )
                    ->default(null)
                    ->preload()
                    ->multiple(),
                SelectFilter::make('rt_id')
                    ->label('RT')
                    ->options(
                        function () {
                            return SLS::with('rt_groups')->get()->pluck('rt_groups.rt_nama', 'rt_groups.rt_id');
                        }
                    )
                    ->default(null)
                    ->preload()
                    ->multiple(),

            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
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
            RelationManagers\SlsKkRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSLS::route('/'),
            'create' => Pages\CreateSLS::route('/create'),
            'edit' => Pages\EditSLS::route('/{record}/edit'),
        ];
    }
}
