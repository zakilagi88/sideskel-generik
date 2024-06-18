<?php

namespace App\Filament\Clusters\HalamanStatistik\Resources;

use App\Filament\Clusters\HalamanStatistik;
use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource\Pages;
use App\Livewire\Widgets\Charts\Stat\SDMBarChart;
use App\Livewire\Widgets\Charts\Stat\SDMPieChart;
use App\Livewire\Widgets\Charts\Stat\SDMPyramidChart;
use App\Models\Penduduk\PendudukView;
use App\Models\StatSDM;
use App\Models\Wilayah;
use App\Services\EnumQueryService;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class StatSDMResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = StatSDM::class;

    protected static ?string $cluster = HalamanStatistik::class;

    protected static ?string $modelLabel = 'Statistik Kependudukan';

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
        $key = $form->getRecord();
        $query = self::getPendudukViewQuery($key);
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\Select::make('key')
                    ->options(EnumQueryService::getEnumOptions())
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
                Tabs::make('grafik')
                    ->columnSpanFull()
                    ->hiddenOn('create')
                    ->tabs([
                        Tab::make('Grafik Bar')
                            ->icon('fas-chart-simple')
                            ->iconPosition(IconPosition::After)
                            ->schema([
                                Livewire::make(SDMBarChart::class, ['chartData' => $query])
                                    ->hiddenOn('create')
                                    ->hidden(fn (?Model $record): bool => $record === null || $record->key === 'umur' || $record->key === 'rentang_umur')
                                    ->label('Grafik Bar Statistik'),
                                Livewire::make(SDMPyramidChart::class, ['chartData' => $query])
                                    ->hiddenOn('create')
                                    ->hidden(fn (?Model $record): bool => $record === null || $record->key !== 'umur' || $record->key !== 'rentang_umur')
                                    ->label('Grafik Piramida Statistik'),
                            ]),
                        Tab::make('Grafik Pie')
                            ->icon('fas-chart-pie')
                            ->iconPosition(IconPosition::After)
                            ->schema([
                                Livewire::make(SDMPieChart::class, ['chartData' => $query])
                                    ->hiddenOn('create')
                                    ->hidden(fn (?Model $record): bool => $record === null)
                                    ->label('Grafik Pie Statistik'),
                            ]),
                    ])


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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()->iconSize(IconSize::Small)->color('success')->modalWidth(MaxWidth::FiveExtraLarge),
                    Tables\Actions\EditAction::make()->iconSize(IconSize::Small)->color('primary'),
                    Tables\Actions\DeleteAction::make()->iconSize(IconSize::Small)->color('danger'),
                ])->icon("fas-gears")->iconPosition('after')->color('success')->button()->label('Aksi'),
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

    protected static function getPendudukViewQuery($record): array
    {
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();

        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;

        $wilayah = $authUser->hasRole('Admin') ? null : $descendants;

        if (is_null($record) || is_null($record->key)) {
            return [];
        } else {
            $query = PendudukView::getView(key: $record->key, wilayahId: $wilayah);

            if ($record->key === 'rentang_umur') {
                $query->orderByRaw("CAST(SUBSTRING_INDEX(rentang_umur, '-', 1) AS UNSIGNED)");
            } elseif ($record->key === 'umur') {
                $query->orderByRaw("CAST(umur AS UNSIGNED)");
            }

            return $query->get()->toArray();
        }
    }
}
