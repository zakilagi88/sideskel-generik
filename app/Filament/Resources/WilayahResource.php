<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WilayahResource\Pages;
use App\Filament\Resources\WilayahResource\RelationManagers;
use App\Models\RT;
use App\Models\RW;
use App\Models\Wilayah;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpSpreadsheet\Calculation\Web;
use stdClass;

class WilayahResource extends Resource
{
    protected static ?string $model = Wilayah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'wilayah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('dusun_id')
                    ->numeric(),
                TextInput::make('rw_id')
                    ->numeric(),
                TextInput::make('rt_id')
                    ->numeric(),
                TextInput::make('kel_id')
                    ->required()
                    ->maxLength(36),
                TextInput::make('wilayah_nama')
                    ->label('Nama Wilayah')
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
                TextInput::make('kec_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kab_id')
                    ->required()
                    ->numeric(),
                TextInput::make('prov_id')
                    ->required()
                    ->maxLength(36),
                TextInput::make('wilayah_kode')
                    ->required()
                    ->maxLength(4),
                TextInput::make('wilayah_kodepos')
                    ->maxLength(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('wilayah_nama')
                    ->label('Nama Wilayah')
                    ->searchable(),
                TextColumn::make('wilayah_kodepos')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dusun.dusun_nama')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rts.rt_nama')
                    ->label('RT')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('rws.rw_nama')
                    ->label('RW')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('kelurahan.kel_nama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('kecamatan.kec_nama')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kabkota.kabkota_nama')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provinsi.prov_nama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('wilayah_kode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('rw_id')
                    ->label('RW')
                    ->options(
                        function () {
                            return Wilayah::with('rws')->get()->pluck('rws.rw_nama', 'rws.rw_id');
                        }
                    )
                    ->default(null)
                    ->preload()
                    ->multiple(),
                SelectFilter::make('rt_id')
                    ->label('RT')
                    ->options(
                        function () {
                            return Wilayah::with('rts')->get()->pluck('rts.rt_nama', 'rts.rt_id');
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
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\WilayahKkRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWilayahs::route('/'),
            'create' => Pages\CreateWilayah::route('/create'),
            'view' => Pages\ViewWilayah::route('/{record}'),
            'edit' => Pages\EditWilayah::route('/{record}/edit'),
        ];
    }
}
