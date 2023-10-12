<?php

namespace App\Filament\Resources;

use App\Enum\Penduduk\Agama;
use App\Enum\Penduduk\JenisKelamin;
use App\Enum\Penduduk\Pekerjaan;
use App\Enum\Penduduk\Pendidikan;
use App\Enum\Penduduk\Pernikahan;
use App\Enum\Penduduk\Status;
use App\Filament\Resources\KartukeluargaResource\Pages;
use App\Filament\Resources\KartukeluargaResource\RelationManagers;
use App\Filament\Resources\KartukeluargaResource\RelationManagers\PenduduksRelationManager;
use App\Filament\Resources\PendudukResource\Pages\ViewPenduduk;
use App\Models\AnggotaKeluarga;
use App\Models\Kab_Kota;
use App\Models\Kartukeluarga;
use App\Models\kecamatan;
use App\Models\Kelurahan;
use App\Models\Penduduk;
use App\Models\Provinsi;
use App\Models\SLS;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action as InfoAction;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\Split as ComponentsSplit;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group as GroupingGroup;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class KartukeluargaResource extends Resource
{
    protected static ?string $model = Kartukeluarga::class;

    protected static ?string $recordTitleAttribute = 'kepalaKeluarga.nama_lengkap';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Kartu Keluarga';

    protected static ?string $slug = 'kartukeluarga';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kartu Keluarga')
                    ->description('Masukkan informasi kartu keluarga')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('kk_id')
                                    ->label('No KK')
                                    ->unique(ignoreRecord: true)
                                    ->dehydrated()
                                    ->placeholder('Masukkan nomor kartu keluarga')
                                    ->dehydrated(
                                        fn (?string $state): bool => filled($state)
                                    )
                                    ->required(fn (string $operation): bool => $operation === 'create'),
                                Select::make('kk_kepala')
                                    ->label('KK Kepala Keluarga')
                                    ->relationship('kepalaKeluarga', 'nama_lengkap')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(
                                        [
                                            KartukeluargaResource::getFormSchema()
                                        ]
                                    )
                                    ->createOptionAction(
                                        function (ActionsAction $action) {
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
                        Group::make()
                            ->relationship('sls')
                            ->schema([
                                Select::make('sls_id')
                                    ->label('RW/RT')
                                    ->options(
                                        fn (Get $get): Collection => SLS::query()
                                            ->where('kel_id', $get('kel_id'))
                                            ->pluck('sls_nama', 'sls_id')
                                    )

                                    ->dehydrated(),

                                Select::make('kel_id')
                                    ->label('Desa/Kelurahan')
                                    ->searchable()
                                    ->options(
                                        function (Get $get, Set $set) {
                                            $kec_id = Kelurahan::query()
                                                ->where('kel_id', $get('kel_id'))
                                                ->pluck('kec_id')
                                                ->first();

                                            $set('kec_id', $kec_id);
                                            return Kelurahan::query()
                                                ->pluck('kel_nama', 'kel_id');
                                        }
                                    )
                                    ->dehydrated(),
                                Select::make('kec_id')
                                    ->label('Kecamatan')
                                    ->searchable()
                                    ->options(
                                        function (Get $get, Set $set) {
                                            $kabkota_id = kecamatan::query()
                                                ->where('kec_id', $get('kec_id'))
                                                ->pluck('kabkota_id')
                                                ->first();
                                            $set('kabkota_id', $kabkota_id);

                                            return kecamatan::query()
                                                ->pluck('kec_nama', 'kec_id');
                                        }
                                    )
                                    ->dehydrated()
                                    ->live()
                                    ->preload(),
                                Select::make('kabkota_id')
                                    ->label('Kab/Kota')
                                    ->searchable()
                                    ->options(
                                        function (Get $get, Set $set) {

                                            // dd($get('kabkota_id'));
                                            $prov_id = Kab_Kota::query()
                                                ->where('kabkota_id', $get('kabkota_id'))
                                                ->pluck('prov_id')
                                                ->first();

                                            $set('prov_id', $prov_id);
                                            return Kab_Kota::query()
                                                ->pluck('kabkota_nama', 'kabkota_id');
                                        }
                                    )
                                    ->dehydrated()
                                    ->live()
                                    ->preload(),
                                Select::make('prov_id')
                                    ->label('Provinsi')
                                    ->searchable()
                                    ->options(
                                        function (Get $get, Set $set) {
                                            $prov_id = Provinsi::query()
                                                ->where('prov_id', $get('prov_id'))
                                                ->pluck('prov_id')
                                                ->first();

                                            $set('prov_id', $prov_id);
                                            return Provinsi::query()
                                                ->pluck('prov_nama', 'prov_id');
                                        }
                                    )
                                    ->dehydrated(),


                            ])->columnSpan(['lg' => 1]),

                    ])->columns(2)->columnSpanFull(),
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

                TextColumn::make('sls.sls_nama')
                    ->label('SLS')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sls.rw_groups.rw_nama')
                    ->label('RW')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable()
                    ->hidden(),
                TextColumn::make('sls.rt_groups.rt_nama')
                    ->label('RT')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->searchable()
                    ->sortable()
                    ->hidden(),

                TextColumn::make('anggotaKeluarga')
                    ->label('Anggota Keluarga')
                    ->copyable()
                    ->copyMessage('Telah Disalin!')
                    ->copyMessageDuration(500)
                    ->formatStateUsing(
                        fn (Kartukeluarga $record) => ($record->anggotaKeluarga())->count()
                    )
                    ->alignCenter(),

                // ->counts('anggotaKK')
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

                        // wrap the state
                        $state = wordwrap($state, $column->getCharacterLimit(), ' ', true);
                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),

            ])

            ->filters([

                SelectFilter::make('sls_id')
                    ->label('RT')
                    ->options(
                        function () {
                            return (SLS::with('rt_groups')->get()->pluck('rt_groups.rt_nama', 'rt_groups.rt_id'));
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

            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Kartu Keluarga Baru')
                    ->icon('heroicon-o-plus')
                    ->url(fn () => route('filament.admin.resources.kartukeluarga.create'))
                    ->button(),
            ])
            ->groups([
                GroupingGroup::make('sls.rt_groups.rt_nama')
                    ->label('RT ')
                    ->collapsible(),
                GroupingGroup::make('sls.rw_groups.rw_nama')
                    ->label('RW ')
                    ->collapsible()
            ])
            // ->groupsInDropdownOnDesktop()
            ->groupRecordsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->icon('heroicon-o-plus')
                    ->label('Kelompokkan'),
            )

            ->emptyStateHeading('Kartu Keluarga belum ada')
            ->emptyStateDescription('Silahkan buat Kartu Keluarga baru dengan menekan tombol berikut:')
            ->striped()
            ->defaultSort('sls_id', 'asc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Informasi Umum')
                    ->schema(
                        [
                            TextEntry::make('kk_id')
                                ->label('Nomor Kartu Keluarga')
                                ->weight(FontWeight::Bold)
                                ->copyable()
                                ->copyMessage('Telah Disalin!')
                                ->copyMessageDuration(1000),
                            TextEntry::make('kepalaKeluarga.nama_lengkap')
                                ->label('Kepala Keluarga')
                                ->weight(FontWeight::Bold)
                                ->copyable()
                                ->copyMessage('Telah Disalin!')
                                ->copyMessageDuration(1000),
                            TextEntry::make('kk_alamat')
                                ->label('Alamat KK')->columnSpan(2)->alignLeft(),
                            TextEntry::make('sls.kel_groups.kel_nama')
                                ->label('Kelurahan'),
                            TextEntry::make('sls.sls_nama')
                                ->label('SLS'),
                        ]
                    )->columnSpan(3)->columnStart(1),
                Fieldset::make('Status Perubahan')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->since(),
                        TextEntry::make('updated_at')
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
                    ])
                    ->columnSpan(1)->columnStart(4)->columns(1),
                ComponentsSection::make('Anggota Keluarga')
                    ->description('Daftar Anggota Keluarga')
                    ->schema([
                        RepeatableEntry::make('anggotaKeluarga')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('penduduk.nama_lengkap')
                                    ->label('Nama Lengkap'),
                                TextEntry::make('nik')
                                    ->label('NIK'),
                                TextEntry::make('hubungan')
                                    ->label('Hubungan'),
                                Actions::make([
                                    InfoAction::make('view')
                                        ->label('Lihat')
                                        ->icon('heroicon-o-eye')
                                        ->url(fn (AnggotaKeluarga $record): string => route('filament.admin.resources.penduduk.view', $record->nik))
                                        ->openUrlInNewTab()
                                ])

                            ])->grid(2)->columnSpan(2)
                    ])->collapsible(),

            ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            PenduduksRelationManager::class,
            AuditsRelationManager::class,

        ];
    }

    public static function getFormSchema(): Component
    {
        return
            Group::make()
            ->schema([
                Section::make()
                    ->heading('Informasi Penduduk')
                    ->description('Silahkan isi data penduduk dengan benar')
                    ->schema([
                        TextInput::make('nik')
                            ->label('NIK')
                            ->unique(AnggotaKeluarga::class, 'nik')
                            ->dehydrated()
                            ->placeholder('Masukkan NIK')
                            ->required(),
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(),
                        Group::make()
                            ->label('Jenis Kelamin')
                            ->schema([
                                Select::make('agama')
                                    ->options(Agama::class)
                                    ->required(),
                                Select::make('jenis_kelamin')
                                    ->options(JenisKelamin::class)
                                    ->required(),
                            ])->columns(2),
                        Group::make()
                            ->label('Tempat dan Tanggal Lahir')
                            ->schema([
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->required(),
                            ])->columns(2),
                    ]),
                Section::make()
                    ->heading('Informasi Tambahan')
                    ->description('Silahkan isi data tambahan penduduk')
                    ->schema([
                        Select::make('pendidikan')
                            ->label('Pendidikan')
                            ->options(Pendidikan::class),
                        Select::make('status_pernikahan')
                            ->label('Status Pernikahan')
                            ->options(Pernikahan::class),
                        Select::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->options(Pekerjaan::class)
                            ->searchingMessage('Mencari Jenis Pekerjaan')
                            ->searchable()
                            ->required(),
                    ])->collapsible(),
                Section::make()
                    ->heading('Status Tempat Tinggal')
                    ->description('Keterangan Status Tempat Tinggal')
                    ->schema(
                        [
                            Select::make('status')
                                ->options(Status::class)
                                ->required(),
                            TextInput::make('alamat')
                                ->label('Alamat')
                                ->required(),
                        ]
                    ),
            ])->columnSpan(['lg' => 2]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartukeluargas::route('/'),
            'create' => Pages\CreateKartukeluarga::route('/create'),
            // 'view' => Pages\ViewKartukeluarga::route('/{record}'),
            'edit' => Pages\EditKartukeluarga::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        ($roles = auth()->user()->roles->pluck('name'));

        if ($roles->contains('RT')) {
            // Pengguna dengan peran RT
            $slsId = auth()->user()->slsRoles->pluck('sls.sls_id')->first();
            $queryRT = parent::getEloquentQuery()->where('sls_id', $slsId);
        } elseif ($roles->contains('RW')) {
            // Pengguna dengan peran RW
            $slsId = auth()->user()->slsRoles->pluck('sls.sls_id')->all();
            $queryRW = parent::getEloquentQuery()->whereIn('sls_id', $slsId);
        } else {
            $queryAdmin = parent::getEloquentQuery();
        }

        return $queryRT ?? $queryRW ?? $queryAdmin;
    }



    public static function getRecordTitle(?Model $record): Htmlable | string
    {
        $kk_id = Arr::get(request()->route()->parameters, 'record');

        $kepalaKeluarga = Kartukeluarga::with('kepalaKeluarga')->find($kk_id);

        //jika state berubah tetapi tidak ada perubahan pada kk_id tetap tampilkan data kepala keluarga sebelumnya
        if (!$kepalaKeluarga) {
            return $record->kepalaKeluarga->nama_lengkap . ' - ' . $record->kk_id;
        }

        //jika state berubah dan ada perubahan pada kk_id tampilkan data kepala keluarga baru
        if ($kepalaKeluarga->kepalaKeluarga->nama_lengkap != $record->kepalaKeluarga->nama_lengkap) {
            return $kepalaKeluarga->kepalaKeluarga->nama_lengkap . ' - ' . $kepalaKeluarga->kk_id;
        }

        return $kepalaKeluarga->kepalaKeluarga->nama_lengkap . ' - ' . $kepalaKeluarga->kk_id;
        // return parent::getRecordTitle($record);
    }
}