<?php

namespace App\Filament\Clusters\HalamanStatistik\Resources;

use App\Filament\Clusters\HalamanStatistik;
use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource\Pages;
use App\Livewire\Widgets\Charts\Stat\SDMBarChart;
use App\Livewire\Widgets\Charts\Stat\SDMPieChart;
use App\Models\Penduduk\PendudukView;
use App\Models\StatSDM;
use App\Models\Wilayah;
use App\Services\GenerateEnumUnionQuery;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class StatSDMResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = StatSDM::class;

    protected static ?string $cluster = HalamanStatistik::class;

    protected static ?string $navigationIcon = '';

    protected static ?string $navigationLabel = 'Statistik';

    protected static ?string $slug = 'kependudukan';

    protected static ?string $breadcrumb = 'Kependudukan';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static bool $shouldRegisterNavigation = false;

    public array $data = [];

    public function mount($record): void
    {
        $this->data = $this->getPendudukViewQuery($record);
    }


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
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\Select::make('key')
                    ->options(GenerateEnumUnionQuery::getEnumOptions())
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
                    ->tabs([
                        Tab::make('Grafik Bar')
                            ->schema([
                                Livewire::make(SDMBarChart::class, ['chartData' => self::getPendudukViewQuery($key)])
                                    ->label('Grafik Bar Statistik'),
                            ]),
                        Tab::make('Grafik Pie')
                            ->schema([
                                Livewire::make(SDMPieChart::class, ['chartData' => self::getPendudukViewQuery($key)])
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

        $query = PendudukView::getView(key: $record->key, wilayahId: $wilayah);


        if ($record->key === 'rentang_umur') {
            $query->orderByRaw("CAST(SUBSTRING_INDEX(rentang_umur, '-', 1) AS UNSIGNED)");
        }

        return $query->get()->toArray();
    }
}