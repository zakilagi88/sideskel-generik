<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources;

use App\Facades\Deskel;
use App\Filament\Clusters\HalamanWilayah;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages;
use App\Models\DeskelProfil;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Filament\Facades\Filament;
use Filament\Forms\Components\{Group, Hidden, Section, Select, TextInput};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group as GroupingGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WilayahResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = Wilayah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = HalamanWilayah::class;

    protected static ?string $slug = 'wilayah';


    public static function form(Form $form): Form
    {
        $deskelProfile = Deskel::getFacadeRoot();

        return $form
            ->schema([
                Hidden::make('deskel_id')
                    ->default(
                        fn () => $deskelProfile->deskel_id ?? null
                    ),
                Select::make('parent_id')
                    ->inlineLabel()
                    ->searchable()
                    ->live()
                    ->label('Wilayah Induk')
                    ->native(false)
                    ->options(
                        fn () => Wilayah::with('parent')->pluck('wilayah_nama', 'wilayah_id')
                    ),
                TextInput::make('wilayah_nama')
                    ->inlineLabel()
                    ->required()
                    ->label('Nama Wilayah')
                    ->maxLength(100),
                Hidden::make('tingkatan')
                    ->required(
                        function (Get $get, Set $set) {
                            $parent = $get('parent_id');
                            if ($parent == null) {
                                $set('tingkatan', 1);
                            } else {
                                (int) $level = Wilayah::tree()->find($parent)->depth;
                                $set('tingkatan', $level + 2);
                            }
                        }
                    ),
                Select::make('wilayah_kepala')
                    ->label('Kepala Wilayah')
                    ->inlineLabel()
                    ->relationship(
                        name: 'kepalaWilayah',
                        titleAttribute: 'nama_lengkap',
                        modifyQueryUsing: fn (Builder $query) => $query->with('kartuKeluarga.wilayah')->orderBy('nama_lengkap')
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama_lengkap} - {$record->kartuKeluarga->wilayah->wilayah_nama}")
                    ->searchable(['nama_lengkap', 'nik'])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        /** @var \App\Models\User */
        $auth = Filament::auth()->user();

        return $table
            ->query(
                static::$model::tree()->depthFirst()
            )
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('wilayah_nama')
                    ->label('Wilayah')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kepalaWilayah.nama_lengkap')
                    ->label('Kepala Wilayah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->filters([
                SelectFilter::make('parent_id')
                    ->options(
                        fn () => Wilayah::isRoot()
                            ->pluck('wilayah_nama', 'wilayah_id')
                    )
                    ->label(''),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn (array $filters): array => [
                Group::make()
                    ->extraAttributes(['class' => 'mb-4'])
                    ->schema([
                        $filters['parent_id'],
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\Action::make('wilayah_kepala')
                    ->label('Kepala Wilayah')
                    ->button()
                    ->color('info')
                    ->icon('fas-user')
                    ->form([
                        Select::make('wilayah_kepala')
                            ->relationship(
                                name: 'kepalaWilayah',
                                titleAttribute: 'nama_lengkap',
                                modifyQueryUsing: fn (Builder $query) => $query->with('kartuKeluarga.wilayah')->orderBy('nama_lengkap')
                            )
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama_lengkap} - {$record->kartuKeluarga->wilayah->wilayah_nama}")
                            ->searchable(['nama_lengkap', 'nik'])
                    ])
                    ->action(
                        fn (Wilayah $record, array $data) =>
                        $record->update(['wilayah_kepala' => $data['wilayah_kepala']])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('wilayah_id', $auth->hasRole('Admin'));;
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
            'index' => Pages\ListWilayahs::route('/'),
            'create' => Pages\CreateWilayah::route('/create'),
            'edit' => Pages\EditWilayah::route('/{record}/edit'),
        ];
    }
}
