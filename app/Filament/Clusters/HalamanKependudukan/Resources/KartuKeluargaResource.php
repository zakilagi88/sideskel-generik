<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources;

use App\Enums\Kependudukan\StatusHubungan;
use App\Facades\Deskel;
use App\Facades\DeskelProfile;
use App\Filament\Clusters\Kependudukan\Kependudukan;
use App\Filament\Clusters\HalamanKependudukan;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\RelationManagers\PenduduksRelationManager;
use App\Models\Deskel\DesaKelurahanProfile;
use App\Models\{KartuKeluarga,  Penduduk, Wilayah};
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\{Form, Get, Set};
use Filament\Forms\Components\{Component, Group, Repeater, Section, Select, Textarea, TextInput, Wizard};
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\{Infolist, Components};
use Filament\Infolists\Components\Actions\Action as InfoAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use Termwind\Components\Dd;

class KartuKeluargaResource extends Resource implements HasShieldPermissions
{
    protected static ?string $breadcrumb = 'Keluarga';


    protected static ?string $model = KartuKeluarga::class;

    protected static ?string $modelLabel = 'Keluarga';

    protected static ?string $recordTitleAttribute = 'kepalaKeluarga.nama_lengkap';

    protected static ?string $navigationIcon = 'fas-people-roof';

    protected static ?string $navigationLabel = 'Data Keluarga';

    protected static ?string $slug = 'keluarga';

    protected static ?string $cluster = HalamanKependudukan::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
            'force_delete',
            'force_delete_any',
            'replicate',
            'reorder',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $settings = app(GeneralSettings::class)->toArray();
        if ($settings['site_active'] == true) {
            return false;
        } else {
            return true;
        }
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Informasi Kartu Keluarga')
                            ->schema([
                                TextInput::make('kk_id')
                                    ->label('Nomor Kartu Keluarga')
                                    ->unique(ignoreRecord: true)
                                    ->dehydrated()
                                    ->placeholder('Masukkan nomor kartu keluarga')
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->required(fn (string $operation): bool => $operation === 'create'),
                                Group::make()
                                    ->relationship('kepalaKeluarga')
                                    ->schema([
                                        Select::make('nama_lengkap')
                                            ->label('Kepala Keluarga')
                                            ->searchable()
                                            ->preload()
                                            ->disabled()
                                            ->required(),
                                    ]),
                                Textarea::make('kk_alamat')
                                    ->label('Alamat')
                                    ->rows(5)
                                    ->placeholder('Masukkan alamat kartu keluarga')
                                    ->required(),
                            ])
                            ->columnStart(1),
                    ])->columns(2)->columnSpanFull(),
                Group::make()
                    ->schema([
                        Section::make('Informasi Wilayah')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Select::make('deskel_id')
                                            ->label('Kelurahan')
                                            ->relationship('dk', 'deskel_nama')
                                            ->disabled()
                                            ->searchable()
                                            ->options(
                                                fn (Get $get): Collection =>
                                                DesaKelurahanProfile::with('dk')->get()->pluck('dk.deskel_nama', 'dk.deskel_id')
                                            )
                                            ->dehydrated(),
                                        Select::make('wilayah_id')
                                            ->label('Kelurahan')
                                            ->relationship('wilayah', 'wilayah_nama')
                                            ->disabled()
                                            ->searchable()
                                            ->options(
                                                fn (Get $get): Collection =>

                                                Wilayah::pluck('wilayah.wilayah_nama', 'wilayah_id')
                                            )

                                    ])->columns(2)->columnSpanFull(),
                            ])
                            ->columnStart(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query->with(['kepalaKeluarga', 'penduduks', 'wilayah', 'parentWilayah'])
            )
            ->columns([
                TextColumn::make('kk_id')
                    ->label('No KK')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kepalaKeluarga.nama_lengkap')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->placeholder('Belum ada Kepala Keluarga'),
                TextColumn::make('wilayah.wilayah_nama')
                    ->label('Wilayah')
                    ->placeholder('Belum ada Wilayah')
                    ->searchable(),
                TextColumn::make('penduduks_count')
                    ->label('Anggota Keluarga')
                    ->counts('penduduks')
                    ->alignCenter(),
                TextColumn::make('kk_alamat')
                    ->label('Alamat KK')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        $state = wordwrap($state, $column->getCharacterLimit(), ' ', true);
                        return $state;
                    }),
            ])
            ->filters([
                Filter::make('parentWilayah')
                    ->form([
                        Group::make([
                            Select::make('parent_id')
                                ->relationship('parentWilayah', 'wilayah_nama')
                                ->hiddenLabel()
                                ->placeholder('Filter Wilayah RW')
                                ->live()
                                ->afterStateUpdated(
                                    function (Set $set) {
                                        $set('children_id', null);
                                    }
                                )
                                ->searchable()
                                ->preload(),
                            Select::make('children_id')
                                ->hiddenLabel()
                                ->placeholder('Filter Wilayah RT')
                                ->searchable()
                                ->options(
                                    fn (Get $get): Collection =>
                                    empty($get('parent_id')) ? Wilayah::where('tingkatan', 2)->pluck('wilayah_nama', 'wilayah_id') :
                                        Wilayah::where('parent_id', $get('parent_id'))->pluck('wilayah_nama', 'wilayah_id')
                                ),
                        ])->columns(2)->columnSpanFull(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['parent_id'],
                                fn (Builder $query): Builder => $query->whereHas('parentWilayah', fn (Builder $query) => $query->where('alias.parent_id', $data['parent_id']))
                            )
                            ->when(
                                $data['children_id'],
                                fn (Builder $query): Builder => $query->where('wilayah_id', $data['children_id'])
                            );
                    }),
                TernaryFilter::make('kepalaKeluarga')
                    ->label('')
                    ->placeholder('Filter Kepala Keluarga')
                    ->trueLabel('Ada kepala keluarga')
                    ->falseLabel('Tidak ada kepala keluarga')
                    ->queries(
                        true: fn (Builder $query) => $query->whereHas('kepalaKeluarga'),
                        false: fn (Builder $query) => $query->whereDoesntHave('kepalaKeluarga'),
                        blank: fn (Builder $query) => $query,
                    ),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn (array $filters): array => [
                Group::make()
                    ->extraAttributes(['class' => 'mb-4'])
                    ->schema([
                        $filters['parentWilayah'],
                        $filters['kepalaKeluarga'],
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->actions(
                [
                    ActionGroup::make([
                        Tables\Actions\EditAction::make()->iconSize(IconSize::Small),
                        Tables\Actions\ViewAction::make()->color('info')->iconSize(IconSize::Small),
                    ])->icon("fas-gears")->iconPosition('after')->color('success')->button()->label('Aksi'),
                ],
                position: ActionsPosition::AfterColumns
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('wilayah.wilayah_nama')
                    ->label('Wilayah ')
                    ->collapsible(),
            ])
            ->groupRecordsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->icon('heroicon-o-plus')
                    ->label('Kelompokkan'),
            )
            ->emptyStateHeading('Kartu Keluarga belum ada')
            ->emptyStateDescription('Silahkan tambahkan Kartu Keluarga baru dengan menekan tombol tambah di atas')
            ->recordUrl(fn (KartuKeluarga $record) => static::getUrl('edit', ['record' => $record->kk_id]))
            ->recordClasses(fn (Model $record) => empty($record->kepalaKeluarga?->nama_lengkap) ? 'bg-red-100' : '')
            ->striped()
            ->poll('60s')
            ->deferLoading();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Tabs::make('Tabs')
                    ->tabs([
                        Components\Tabs\Tab::make('Informasi Kartu Keluarga')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->iconSize('md')
                            ->schema(
                                [
                                    Components\Fieldset::make('Detail Kartu Keluarga')
                                        ->schema([
                                            Components\TextEntry::make('kk_id')
                                                ->label('Nomor Kartu Keluarga')
                                                ->copyable()
                                                ->copyMessage('Telah Disalin!')
                                                ->copyMessageDuration(1000),
                                            Components\TextEntry::make('kepalaKeluarga.nama_lengkap')
                                                ->label('Kepala Keluarga')
                                                ->placeholder('Belum ada Kepala Keluarga')
                                                ->copyable()
                                                ->copyMessage('Telah Disalin!')
                                                ->copyMessageDuration(1000),
                                            Components\TextEntry::make('kk_alamat')
                                                ->label('Alamat KK')->alignJustify(),
                                            Components\TextEntry::make('dk.deskel_nama')
                                                ->label('Kelurahan')
                                                ->alignJustify(),

                                        ])->columns(2)->columnSpanFull(),
                                    Components\Fieldset::make('Status Perubahan')
                                        ->schema([
                                            Components\TextEntry::make('created_at')
                                                ->label('Dibuat Pada')
                                                ->since(),
                                            Components\TextEntry::make('updated_at')
                                                ->label('Diubah Pada')
                                                ->formatStateUsing(
                                                    function (Kartukeluarga $record) {
                                                        if ($record->editor) {
                                                            return $record->updated_at->diffForHumans() . ' oleh ' . $record->editor?->name;
                                                        } else {
                                                            return 'Belum ada yang mengubah';
                                                        }
                                                    }
                                                )
                                        ]),
                                ]
                            ),
                        Components\Tabs\Tab::make('Anggota Keluarga')
                            ->icon('heroicon-o-users')
                            ->iconSize('md')
                            ->schema([
                                Components\RepeatableEntry::make('penduduks')
                                    ->hiddenLabel()
                                    ->schema([
                                        Components\TextEntry::make('nama_lengkap')
                                            ->label('Nama Lengkap'),
                                        Components\TextEntry::make('nik')
                                            ->label('NIK'),
                                        Components\TextEntry::make('tanggal_lahir')
                                            ->label('Tanggal Lahir')
                                            ->formatStateUsing(function ($state, Penduduk $record) {
                                                $formattedDate = Carbon::parse($record->tanggal_lahir)->format('d-m-Y');
                                                return $record->tempat_lahir . ', ' . $formattedDate;
                                            })
                                            ->alignLeft(),
                                        Components\TextEntry::make('status_hubungan')
                                            ->label('Status Hubungan'),
                                        Components\Actions::make([
                                            InfoAction::make('view')
                                                ->label('Lihat')
                                                ->icon('heroicon-o-eye')
                                                ->url(fn (Penduduk $record): string => route('filament.admin.kependudukan.resources.penduduk.view', $record->nik))
                                                ->openUrlInNewTab()
                                        ])->alignJustify(),

                                    ])->alignJustify()->columns(2)->grid(1),
                            ])->columnSpanFull(),


                    ])->columnSpanFull(),
            ])->columns(2);
    }

    public static function getFormSchema(): Component
    {
        return
            Wizard::make([
                Step::make('Informasi Kartu Keluarga')
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('Informasi Kartu Keluarga')
                                    ->description('Detail Kartu Keluarga')
                                    ->schema([
                                        TextInput::make('kk_id')
                                            ->label('Nomor Kartu Keluarga')
                                            ->unique(ignoreRecord: true)
                                            ->numeric()
                                            ->disabled()
                                            ->default(fn () => (string) rand(1000000000000000, 9999999999999999))
                                            ->minLength(16)
                                            ->required(),
                                        Select::make('kk_kepala')
                                            ->label('Kepala Keluarga')
                                            ->relationship('kepalaKeluarga', 'nama_lengkap')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(
                                                [
                                                    PendudukResource::getFormSchema()
                                                ]
                                            )
                                            ->createOptionAction(
                                                function (Forms\Components\Actions\Action $action) {
                                                    return $action
                                                        ->label('Tambah Penduduk')
                                                        ->modalWidth('7xl')
                                                        ->modalHeading('Tambah Penduduk');
                                                }
                                            )
                                            ->options(
                                                function (Get $get) {
                                                    if ($get('kk_kepala')) {
                                                        return Penduduk::query()
                                                            ->where('nik', $get('kk_kepala'))
                                                            ->pluck('nama_lengkap', 'nik');
                                                    } else {
                                                        return Penduduk::query()->whereDoesntHave('kartuKeluargas')
                                                            ->pluck('nama_lengkap', 'nik');
                                                    }
                                                }
                                            )
                                            ->required(),
                                        Textarea::make('kk_alamat')
                                            ->label('Alamat')
                                            ->rows(5)
                                            ->placeholder('Masukkan alamat kartu keluarga')
                                            ->required(),
                                    ])
                                    ->columnStart(1),
                            ])->columns(2)->columnSpanFull(),

                    ])
            ]);
    }

    public static function getKartuKeluargaFormSchema(): array
    {
        return [];
    }

    public static function getAnggotaKeluargaFormSchema(): Repeater
    {
        return Repeater::make('anggotaKeluarga')
            ->key('anggotaKeluarga')
            ->defaultItems(
                function (Get $get) {
                    $count = 0;
                    ($get('cek_anggota') === 'Tidak') ? $count  : $count++;

                    return $count;
                }
            )
            ->reactive()
            ->hiddenLabel()
            ->collapsible()
            ->schema(
                PendudukResource::getPendudukFormSchema()
            )
            ->collapseAllAction(
                fn (ActionsAction $action) => $action->label('Tutup Semua'),
            )
            ->itemLabel(
                function (array $state): ?string {
                    $nik = $state['nik'] ?? null;
                    $hubungan = $state['hubungan'] ?? null;
                    $penduduk = Penduduk::where('nik', $nik)->first();
                    $nama = $penduduk->nama_lengkap ?? null;
                    return $nama . ' - ' . $nik . ' - ' . $hubungan;
                }
            )
            ->deleteAction(
                fn (ActionsAction $action) => $action->requiresConfirmation(),
            );
    }

    public static function getRelations(): array
    {
        return [
            PenduduksRelationManager::class,
            AuditsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartukeluargas::route('/'),
            'edit' => Pages\EditKartukeluarga::route('/{record}/edit'),
            'create' => Pages\CreateKartukeluarga::route('/tambah_masuk'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;

        return parent::getEloquentQuery()->byWilayah($authUser, $descendants);
    }



    public static function getRecordTitle(?Model $record): Htmlable | string
    {

        return ($record->kepalaKeluarga?->nama_lengkap ?? 'Belum ada Kepala') . ' - ' . $record->kk_id;
    }
}
