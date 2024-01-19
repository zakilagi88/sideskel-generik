<?php

namespace App\Filament\Resources;

use App\Enum\Penduduk\StatusHubungan;
use App\Filament\Resources\KartukeluargaResource\Pages;
use App\Filament\Resources\KartukeluargaResource\RelationManagers\PenduduksRelationManager;
use App\Models\{KabKota, KartuKeluarga, Kecamatan, Kelurahan, Penduduk, Provinsi, Wilayah};
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\{Form, Get, Set};
use Filament\Forms\Components\{Component, Group, Repeater, Section, Select, Textarea, TextInput, Wizard};
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\{Infolist, Components};
use Filament\Infolists\Components\Actions\Action as InfoAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class KartukeluargaResource extends Resource
{
    protected static ?string $model = KartuKeluarga::class;

    protected static ?string $recordTitleAttribute = 'kepalaKeluarga.nama_lengkap';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Kartu Keluarga';

    protected static ?string $slug = 'kartukeluarga';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Informasi Kartu Keluarga')
                            ->description('Detail Kartu Keluarga')
                            ->schema([
                                TextInput::make('kk_id')
                                    ->label('Nomor Kartu Keluarga')
                                    ->unique(ignoreRecord: true)
                                    ->dehydrated()
                                    ->placeholder('Masukkan nomor kartu keluarga')
                                    ->dehydrated(
                                        fn (?string $state): bool => filled($state)
                                    )
                                    ->required(fn (string $operation): bool => $operation === 'create'),
                                Select::make('kk_kepala')
                                    ->label('Kepala Keluarga')
                                    ->relationship('kepalaKeluarga', 'nama_lengkap')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(
                                        [
                                            PendudukResource::getPendudukFormSchema()
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
                                                return Penduduk::query()->whereDoesntHave('kartuKeluarga')
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
                Group::make()
                    ->schema([
                        Section::make('Informasi Wilayah')
                            ->description('Detail Wilayah')
                            ->schema([
                                Group::make()
                                    ->relationship('wilayah')
                                    ->schema([
                                        Select::make('prov_id')
                                            ->label('Provinsi')
                                            ->options(
                                                fn (): Collection => Provinsi::query()
                                                    ->pluck('prov_nama', 'prov_id')
                                            )
                                            ->live()
                                            ->dehydrated(),

                                        Select::make('kabkota_id')
                                            ->label('Kab/Kota')
                                            ->searchable()
                                            ->options(
                                                fn (Get $get): Collection => KabKota::query()
                                                    ->where('prov_id', $get('prov_id'))
                                                    ->pluck('kabkota_nama', 'kabkota_id')
                                            )
                                            ->dehydrated(),

                                        Select::make('kec_id')
                                            ->label('Kecamatan')
                                            ->searchable()
                                            ->options(
                                                fn (Get $get): Collection => Kecamatan::query()
                                                    ->where('kabkota_id', $get('kabkota_id'))
                                                    ->pluck('kec_nama', 'kec_id')

                                            )
                                            ->dehydrated()
                                            ->preload(),
                                        Select::make('kel_id')
                                            ->label('Kelurahan')
                                            ->searchable()
                                            ->options(
                                                fn (Get $get): Collection => Kelurahan::query()
                                                    ->where('kec_id', $get('kec_id'))
                                                    ->pluck('kel_nama', 'kel_id')
                                            )
                                            ->dehydrated(),
                                        Select::make('wilayah_id')
                                            ->label('RW/RT')
                                            ->searchable()
                                            ->options(
                                                fn (Get $get): Collection => Wilayah::query()
                                                    ->where('kel_id', $get('kel_id'))
                                                    ->pluck('wilayah_nama', 'wilayah_id')
                                            )
                                            ->dehydrated()
                                            ->preload(),
                                        Select::make('wilayah_kodepos')
                                            ->label('Kode Pos')
                                            // ->options(
                                            //     fn (Get $get): Collection => Wilayah::query()
                                            //         ->where('wilayah_id', $get('wilayah_id'))
                                            //         ->pluck('wilayah_kodepos', 'wilayah_kodepos')
                                            // )
                                            ->dehydrated()
                                            ->preload(),

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
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500),
                TextColumn::make('wilayah.wilayah_nama')
                    ->label('Wilayah')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('wilayah.rws.rw_nama')
                    ->label('RW')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('wilayah.rts.rt_nama')
                    ->label('RT')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // TextColumn::make('penduduks')
                //     ->label('Anggota Keluarga')
                //     ->copyable()
                //     ->copyMessage('Telah Disalin!')
                //     ->copyMessageDuration(500)
                //     ->formatStateUsing(
                //         fn (KartuKeluarga $record) => ($record->penduduks())->count()
                //     )
                //     ->alignCenter(),

                TextColumn::make('kk_alamat')
                    ->label('Alamat KK')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->sortable()
                    ->wrap()
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

                SelectFilter::make('wilayah_id')
                    ->label('RT')
                    ->options(
                        function () {
                            return (Wilayah::with('rts')->get()->pluck('rts.rt_nama', 'rts.rt_id'));
                        }
                    )
                    ->default(null)
                    ->preload()
                    ->multiple(),
            ])
            ->actions(
                [
                    Tables\Actions\ViewAction::make()->label('')->iconButton()->iconSize('md')->extraAttributes([
                        'class' => 'text-green-500 hover:text-green-700 mr-2',
                    ]),
                    Tables\Actions\EditAction::make()->label('')->iconButton()->iconSize('md')->extraAttributes([
                        'class' => 'text-yellow-500 hover:text-yellow-700 ',
                    ]),
                ],
                position: ActionsPosition::BeforeColumns
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('wilayah.rts.rt_nama')
                    ->label('RT ')
                    ->collapsible(),
                Tables\Grouping\Group::make('wilayah.rws.rw_nama')
                    ->label('RW ')
                    ->collapsible()
            ])
            ->groupRecordsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->icon('heroicon-o-plus')
                    ->label('Kelompokkan'),
            )
            ->emptyStateHeading('Kartu Keluarga belum ada')
            ->emptyStateDescription('Silahkan buat Kartu Keluarga baru dengan menekan tombol berikut:')
            ->striped()
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50, 100, 'all'])
            ->poll('60s')
            ->deferLoading()
            ->defaultSort('wilayah_id', 'asc');
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
                                                ->weight(FontWeight::Bold)
                                                ->copyable()
                                                ->copyMessage('Telah Disalin!')
                                                ->copyMessageDuration(1000),
                                            Components\TextEntry::make('kepalaKeluarga.nama_lengkap')
                                                ->label('Kepala Keluarga')
                                                ->weight(FontWeight::Bold)
                                                ->copyable()
                                                ->copyMessage('Telah Disalin!')
                                                ->copyMessageDuration(1000),
                                            Components\TextEntry::make('kk_alamat')
                                                ->label('Alamat KK')->alignJustify(),
                                            Components\TextEntry::make('wilayah.kelurahan.kel_nama')
                                                ->label('Kelurahan'),
                                            Components\TextEntry::make('wilayah.wilayah_nama')
                                                ->label('Wilayah'),
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
                                                            return $record->updated_at->diffForHumans() . ' oleh ' . $record->editor->name;
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
                                                ->url(fn (Penduduk $record): string => route('filament.admin.resources.penduduk.view', $record->nik))
                                                ->openUrlInNewTab()
                                        ])->alignJustify(),

                                    ])->alignJustify()->columns(2)->grid(2),
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
                                                        return Penduduk::query()->whereDoesntHave('kartuKeluarga')
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
        return [
            Group::make()
                ->schema([
                    TextInput::make('kk_id')
                        ->label('Nomor Kartu Keluarga')
                        ->minLength(16)
                        ->unique(ignoreRecord: true)
                        ->numeric()
                        ->disabled()
                        ->default(fn () => (string) rand(1000000000000000, 9999999999999999))
                        ->placeholder('Masukkan nomor kartu keluarga')
                        ->dehydrated(
                            fn (?string $state): bool => filled($state)
                        )
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    Select::make('wilayah_id')
                        ->relationship('wilayah', 'wilayah_nama')
                        ->label('Wilayah')
                        ->searchable()
                        ->preload()
                        ->options(
                            StatusHubungan::class
                            // fn (Get $get): Collection => Wilayah::query()
                            //     ->pluck('wilayah_nama', 'wilayah_id')
                        ),
                ])->columns(2),
            Textarea::make('kk_alamat')
                ->label('Alamat')
                ->rows(3)
                ->placeholder('Masukkan alamat kartu keluarga')
                ->required(),

        ];
    }

    public static function getAnggotaKeluargaFormSchema(): Repeater
    {
        return Repeater::make('anggotaKeluarga')
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
                    // dump($state);
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
            'create' => Pages\CreateKartukeluarga::route('/tambah'),
            // 'view' => Pages\ViewKartukeluarga::route('/{record}'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        ($roles = auth()->user()->roles->pluck('name'));

        if ($roles->contains('RT')) {
            // Pengguna dengan peran RT
            $wilayahId = auth()->user()->wilayahRoles->pluck('wilayah.wilayah_id')->first();
            $queryRT = parent::getEloquentQuery()->where('wilayah_id', $wilayahId);
        } elseif ($roles->contains('RW')) {
            // Pengguna dengan peran RW
            $wilayahId = auth()->user()->wilayahRoles->pluck('wilayah.wilayah_id')->all();
            $queryRW = parent::getEloquentQuery()->whereIn('wilayah_id', $wilayahId);
        } else {
            $queryAdmin = parent::getEloquentQuery();
        }

        return $queryRT ?? $queryRW ?? $queryAdmin;
    }



    public static function getRecordTitle(?Model $record): Htmlable | string
    {
        $kk_id = Arr::get(request()->route()->parameters, 'record');

        $kepalaKeluarga = Kartukeluarga::with('penduduks')->find($kk_id);

        //jika state berubah tetapi tidak ada perubahan pada kk_id tetap tampilkan data kepala keluarga sebelumnya
        if (!$kepalaKeluarga) {
            return $record->nama_lengkap . ' - ' . $record->kk_id;
        }

        //jika state berubah dan ada perubahan pada kk_id tampilkan data kepala keluarga baru
        if ($kepalaKeluarga->nama_lengkap != $record->nama_lengkap) {
            return $kepalaKeluarga->nama_lengkap . ' - ' . $kepalaKeluarga->kk_id;
        }

        return $kepalaKeluarga->nama_lengkap . ' - ' . $kepalaKeluarga->kk_id;
    }
}