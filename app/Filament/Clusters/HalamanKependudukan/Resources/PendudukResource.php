<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources;

use App\Enums\Kependudukan\{AgamaType, EtnisSukuType, GolonganDarahType, JenisKelaminType, KewarganegaraanType, PendidikanType, PekerjaanType, StatusPengajuanType, PerkawinanType, StatusDasarType, StatusHubunganType, StatusTempatTinggalType, UmurType};
use App\Filament\Clusters\HalamanKependudukan;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource\Pages;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource\Widgets\PendudukOverview;
use App\Filament\Exports\PendudukExporter;
use App\Models\{Bantuan, Kepindahan, Kematian, Penduduk, Dinamika, Wilayah};
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Facades\Filament;
use Filament\Forms\{Form, Get, Set};
use Filament\Forms\Components\{Checkbox, Group, Section, Select, TextInput, DatePicker, Fieldset, FileUpload, Grid as FormsGrid, Hidden, Placeholder, Split as ComponentsSplit, Textarea, TimePicker, Toggle};
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Resource;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\{Actions, Grid, Group as ComponentsGroup, IconEntry, ImageEntry, Section as ComponentsSection, Split, TextEntry};
use Filament\Infolists\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action as ActionsAction, ActionGroup, BulkAction, ExportAction};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class PendudukResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    protected static ?string $navigationIcon = 'fas-people-group';

    protected static ?string $navigationLabel = 'Data Penduduk';

    protected static ?string $slug = 'penduduk';

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
            ->schema(
                [
                    Group::make()
                        ->schema([
                            Section::make()
                                ->heading('Informasi Penduduk')
                                ->description('Silahkan isi data penduduk dengan benar')
                                ->schema([
                                    Group::make()
                                        ->schema([
                                            FileUpload::make('foto')
                                                ->hiddenLabel()
                                                ->alignCenter()
                                                ->getUploadedFileNameForStorageUsing(
                                                    fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                        ->prepend('gambar-penduduk-'),
                                                )
                                                ->disk('public')
                                                ->directory('penduduk')
                                                ->moveFiles()
                                                ->avatar()
                                                ->image()
                                                ->imageEditor()
                                                ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                                                ->panelAspectRatio('2:3')
                                                ->panelLayout('integrated')
                                                ->imagePreviewHeight('300')

                                                ->loadingIndicatorPosition('right')
                                                ->removeUploadedFileButtonPosition('right')
                                                ->uploadProgressIndicatorPosition('left')
                                                ->columnStart(['lg' => 1, 'md' => 1]),
                                            Group::make()
                                                ->schema([
                                                    TextInput::make('nik')
                                                        ->label('NIK')
                                                        ->unique(ignoreRecord: true)
                                                        ->live()
                                                        ->afterStateUpdated(function (HasForms $livewire, TextInput $component) {
                                                            /** @var Livewire $livewire */
                                                            $livewire->validateOnly($component->getStatePath());
                                                        })
                                                        ->required(),
                                                    TextInput::make('nama_lengkap')
                                                        ->label('Nama Lengkap')
                                                        ->required(),
                                                    Select::make('kk_id')
                                                        ->label('No. KK')
                                                        ->relationship('kartuKeluargas', 'kk_id')
                                                        ->disabled()
                                                        ->required(),
                                                ]),
                                        ])->columns(2),
                                    Group::make()
                                        ->schema([
                                            Select::make('jenis_identitas')
                                                ->label('Jenis Identitas')
                                                ->placeholder('Pilih Jenis Identitas')
                                                ->options([
                                                    'KTP' => 'KTP',
                                                    'E-KTP' => 'E-KTP',
                                                    'KIA' => 'KIA',
                                                    'BELUM WAJIB' => 'BELUM WAJIB'
                                                ])
                                                ->required(),
                                            TextInput::make('wilayah_id')
                                                ->label('Wilayah')
                                                ->placeholder('Pilih Wilayah')
                                                ->formatStateUsing(fn(Penduduk $record) => $record->wilayah?->wilayah_nama ?? 'Wilayah Tidak Diketahui')
                                                ->disabled()
                                                ->required(),
                                        ])->columns(2),
                                    Group::make()
                                        ->label('Jenis Kelamin')
                                        ->schema([
                                            Select::make('agama')
                                                ->options(AgamaType::class)
                                                ->required(),
                                            Select::make('jenis_kelamin')
                                                ->options(JenisKelaminType::class)
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
                                        ->options(PendidikanType::class),
                                    Select::make('status_perkawinan')
                                        ->label('Status Perkawinan')
                                        ->options(PerkawinanType::class),
                                    Select::make('pekerjaan')
                                        ->label('Pekerjaan')
                                        ->options(PekerjaanType::class)
                                        ->searchingMessage('Mencari Jenis Pekerjaan')
                                        ->searchable()
                                        ->required(),

                                ])->collapsible(),
                        ])->columnSpan(['lg' => 2]),
                    Group::make()
                        ->schema([
                            Group::make()
                                ->schema([
                                    Section::make()
                                        ->schema(
                                            [
                                                Placeholder::make('created_at')
                                                    ->label('Dibuat Pada')
                                                    ->content(fn(Penduduk $record): ?string => ($record->created_at?->diffForHumans()))
                                                    ->disabledOn('create'),
                                                Placeholder::make('updated_at')
                                                    ->label('Diubah Pada')
                                                    ->content(function (Penduduk $record) {
                                                        if ($record->audits()->count() > 0) {
                                                            $latestAudit = $record->audits()->latest()->first();
                                                            $userName = $latestAudit->user->name;
                                                            $timeDiff = $latestAudit->updated_at->diffForHumans();

                                                            return $timeDiff . ' oleh ' . $userName;
                                                        } else {
                                                            return 'Belum ada yang mengubah';
                                                        }
                                                    })
                                                    ->disabledOn('create')
                                            ]
                                        )->hidden(fn(?Penduduk $record) => $record === null),
                                ]),
                            Section::make()
                                ->heading('Alamat')
                                ->description('Alamat Tempat Tinggal')
                                ->schema([
                                    TextInput::make('alamat_sekarang')
                                        ->label('Alamat Sekarang')
                                        ->required(),
                                    TextInput::make('alamat_sebelumnya')
                                        ->label('Alamat Sebelumnya'),
                                    Select::make('status_tempat_tinggal')
                                        ->label('Status Tempat Tinggal')
                                        ->options(StatusTempatTinggalType::class),
                                ]),
                            Section::make()
                                ->heading('Status ')
                                ->description('Keterangan Status Penduduk')
                                ->schema(
                                    [
                                        Hidden::make('status_pengajuan')
                                            ->default(fn(Penduduk $record) => $record->status_pengajuan->value),
                                        Placeholder::make('keterangan')
                                            ->label('Keterangan')
                                            ->content(function (Penduduk $record) {

                                                $matchColor = match ($record->status_pengajuan->getColor()) {
                                                    'info' => 'bg-info-400',
                                                    'danger' => 'bg-danger-400',
                                                    'warning' => 'bg-warning-400',
                                                    default => 'bg-primary-400',
                                                };

                                                return new HtmlString(
                                                    '<span class="rounded-lg p-2 text-white ' . $matchColor . '">' . $record->status_pengajuan->getLabel() . '</span>'
                                                );
                                            }),
                                        Select::make('status_dasar')
                                            ->disabled()
                                            ->label('Status Dasar')
                                            ->options(StatusDasarType::class),
                                        Toggle::make('is_nik_sementara')
                                            ->label('NIK Sementara')
                                            ->default(false)
                                            ->onColor('success')
                                            ->offColor('danger'),
                                    ]
                                ),
                        ])->columnSpan(['lg' => 1]),
                ]
            )->columns(3);
    }

    public static function table(Table $table): Table
    {
        $freezeColoumn = [
            'style' => 'position: sticky; left: 0;',
        ];

        /** @var \App\Models\User */
        $auth = Filament::auth()->user();
        return $table
            ->columns([
                TextColumn::make('kk_id')
                    ->html()
                    ->color('primary')
                    ->label(
                        fn() => new HtmlString(
                            '<p class="text-sm text-left">No. KK</p> <p class="text-sm text-gray-500 text-left">Kepala Keluarga</p>'
                        )
                    )
                    ->searchable()
                    ->url(fn($record) => KartuKeluargaResource::getUrl('edit', ['record' => $record->kk_id]))
                    ->extraHeaderAttributes($freezeColoumn)
                    ->extraCellAttributes(array_merge($freezeColoumn, ['class' => 'to-be-striped']))
                    ->description(fn(Penduduk $record) => ($record->kartuKeluargas?->kepalaKeluarga?->nama_lengkap) ?: 'Tidak Diketahui'),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->color('primary')
                    ->url(fn($record) => static::getUrl('edit', ['record' => $record->nik]))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->formatStateUsing(fn($state) => ucwords(strtolower($state)))
                    ->sortable(),
                TextColumn::make('wilayah.wilayah_nama')
                    ->placeholder('Wilayah Tidak Diketahui')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenis_kelamin')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('tempat_lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('tanggal_lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('agama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('pendidikan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('status_perkawinan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('status_hubungan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('pekerjaan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('alamat_sekarang')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('alamat_sebelumnya')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('status_dasar')
                    ->searchable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('status_pengajuan')
                    ->searchable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('wilayah')
                    ->relationship('wilayah', 'wilayah_nama')
                    ->preload()
                    ->label('Wilayah'),
                SelectFilter::make('jenis_kelamin')
                    ->options(JenisKelaminType::class)
                    ->label('Jenis Kelamin'),
                SelectFilter::make('agama')
                    ->options(AgamaType::class)
                    ->label('Agama'),
                SelectFilter::make('pekerjaan')
                    ->options(PekerjaanType::class)
                    ->label('Pekerjaan'),
                SelectFilter::make('umur')
                    ->options(UmurType::class)
                    ->label('Umur'),
                SelectFilter::make('pendidikan')
                    ->options(PendidikanType::class)
                    ->label('Pendidikan'),
                SelectFilter::make('status_hubungan')
                    ->options(StatusHubunganType::class)
                    ->label('Status Hubungan'),
                SelectFilter::make('status_perkawinan')
                    ->options(PerkawinanType::class)
                    ->label('Status Perkawinan'),
                SelectFilter::make('status_pengajuan')
                    ->options(StatusPengajuanType::class)
                    ->label('Status Pengajuan'),

            ], FiltersLayout::AboveContentCollapsible)
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->deferFilters()
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn(array $filters): array => [
                Group::make()
                    ->extraAttributes(['class' => 'mb-4'])
                    ->schema([
                        $filters['wilayah'],
                        $filters['jenis_kelamin'],
                        $filters['agama'],
                        $filters['pekerjaan'],
                        $filters['pendidikan'],
                        $filters['umur'],
                        $filters['status_hubungan'],
                        $filters['status_perkawinan'],
                        $filters['status_pengajuan'],
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(PendudukExporter::class)
                    ->color('primary')
                    ->label('Ekspor Data')
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->columnMapping(),
            ])
            ->actions(
                [
                    ActionGroup::make([
                        ActionsAction::make('Ubah Status Dasar')
                            ->icon('fas-pen-to-square')
                            ->iconSize(IconSize::Small)
                            ->color('primary')
                            ->form([
                                Select::make('status_dasar')
                                    ->options(
                                        [
                                            'MENINGGAL' => 'Meninggal',
                                            'PINDAH' => 'Pindah',
                                        ]
                                    )
                                    ->live()
                                    ->afterStateUpdated(
                                        fn(Select $component) => $component
                                            ->getContainer()
                                            ->getComponent('statusDasarFields')
                                            ->getChildComponentContainer()
                                            ->fill()
                                    )
                                    ->required(),
                                FormsGrid::make(1)
                                    ->schema(fn(Get $get): array => match ($get('status_dasar')) {
                                        'MENINGGAL' => [
                                            TextInput::make('tempat_kematian')
                                                ->label('Tempat Meninggal')
                                                ->required(),
                                            TimePicker::make('waktu_kematian')
                                                ->label('Waktu Meninggal')
                                                ->required(),
                                            Select::make('penyebab_kematian')
                                                ->label('Penyebab Meninggal')
                                                ->options([
                                                    'Sakit' => 'Sakit',
                                                    'Wabah Penyakit' => 'Wabah Penyakit',
                                                    'Kecelakaan' => 'Kecelakaan',
                                                    'Bunuh Diri' => 'Bunuh Diri',
                                                    'Kriminalitas' => 'Kriminalitas',
                                                    'Lainnya' => 'Lainnya',
                                                ])
                                                ->required(),
                                            Select::make('menerangkan_kematian')
                                                ->label('Yang menerangkan Meninggal')
                                                ->options([
                                                    'Dokter' => 'Dokter',
                                                    'Tenaga Kesehatan' => 'Tenaga Kesehatan',
                                                    'Kepolisian' => 'Kepolisian',
                                                    'Lainnya' => 'Lainnya',
                                                ])
                                                ->required(),
                                        ],
                                        'PINDAH' => [
                                            Select::make('tujuan_pindah')
                                                ->label('Tujuan Pindah')
                                                ->options([
                                                    'Pindah keluar Desa/Kelurahan' => 'Pindah keluar Desa/Kelurahan',
                                                    'Pindah keluar Kecamatan' => 'Pindah keluar Kecamatan',
                                                    'Pindah keluar Kabupaten/Kota' => 'Pindah keluar Kabupaten/Kota',
                                                    'Pindah keluar Provinsi' => 'Pindah keluar Provinsi',
                                                    'Pindah ke Luar Negeri' => 'Pindah ke Luar Negeri',
                                                ])
                                                ->required(),
                                            Textarea::make('alamat_pindah')
                                                ->autosize()
                                                ->label('Alamat Tujuan Pindah')
                                                ->required(),
                                        ],
                                        default => [],
                                    })->key('statusDasarFields'),
                                Group::make()
                                    ->schema([
                                        DatePicker::make('tanggal_dinamika')
                                            ->label('Tanggal Dinamika'),
                                        DatePicker::make('tanggal_lapor')

                                            ->label('Tanggal Lapor')
                                            ->default(now()),
                                    ]),
                                Textarea::make('catatan_dinamika')
                                    ->label('Keterangan')
                                    ->autosize()
                                    ->required(),
                            ])
                            ->action(
                                function (Penduduk $record, array $data) {

                                    try {
                                        $record->update(['status_dasar' => $data['status_dasar']]);

                                        switch ($data['status_dasar']) {
                                            case 'MENINGGAL':
                                                if ($record->dinamikas()->exists()) {
                                                    $record->dinamikas()->delete();
                                                }

                                                $record->kematian()->firstOrCreate(
                                                    [
                                                        'nik' => $record->nik,
                                                        'waktu_kematian' => $data['waktu_kematian'],
                                                        'tempat_kematian' => $data['tempat_kematian'],
                                                        'penyebab_kematian' => $data['penyebab_kematian'],
                                                        'menerangkan_kematian' => $data['menerangkan_kematian'],
                                                    ]
                                                );

                                                break;
                                            case 'PINDAH':
                                                if ($record->dinamikas()->exists()) {
                                                    $record->dinamikas()->delete();
                                                }

                                                $record->kepindahan()->firstOrCreate(
                                                    [
                                                        'nik' => $record->nama_lengkap,
                                                        'tujuan_pindah' => $data['tujuan_pindah'],
                                                        'alamat_pindah' => $data['alamat_pindah'],
                                                    ]
                                                );

                                                break;
                                        };

                                        $dinamikaType = match ($data['status_dasar']) {
                                            'MENINGGAL' => Kematian::class,
                                            'PINDAH' => Kepindahan::class,
                                        };

                                        $dinamikaId = match ($data['status_dasar']) {
                                            'MENINGGAL' => $record->kematian->id,
                                            'PINDAH' => $record->kepindahan->id,
                                        };

                                        $dinamikaJenis = match ($data['status_dasar']) {
                                            'MENINGGAL' => 'Meninggal',
                                            'PINDAH' => $data['tujuan_pindah'],
                                        };

                                        $dinamika = Dinamika::create(
                                            [
                                                'dinamika_type' => $dinamikaType,
                                                'dinamika_id' => $dinamikaId,
                                                'jenis_dinamika' => $dinamikaJenis,
                                                'catatan_dinamika' => $data['catatan_dinamika'],
                                                'tanggal_dinamika' => $data['tanggal_dinamika'],
                                                'tanggal_lapor' => $data['tanggal_lapor'],
                                            ]
                                        );

                                        $dinamika->penduduk()->associate($record)->save();

                                        return Notification::make()
                                            ->title('Berhasil')
                                            ->body('Status dasar penduduk ' . $record->nama_lengkap . ' berhasil diubah')
                                            ->success()
                                            ->seconds(15)
                                            ->send();
                                    } catch (\Exception $e) {
                                        return Notification::make()
                                            ->title('Gagal')
                                            ->body(
                                                'Gagal mengubah status dasar penduduk ' . $record->nama_lengkap . ' karena ' . $e->getMessage()
                                            )
                                            ->danger()
                                            ->seconds(60)
                                            ->send();
                                    }
                                }
                            ),
                        Tables\Actions\EditAction::make()->iconSize(IconSize::Small)->color('primary')->icon('fas-pen-to-square'),
                        ActionsAction::make('Verifikasi')
                            ->action(fn(Penduduk $record) => $record->update(['status_pengajuan' => StatusPengajuanType::DIVERIFIKASI->value]))
                            ->color('success')->label('Verifikasi')->icon('fas-check')
                            ->requiresConfirmation()->after(fn(Penduduk $record) => Notification::make()
                                ->title('Penduduk ' . $record->nama_lengkap . ' Berhasil di Perbarui')
                                ->body($record->nama_lengkap . ' sudah diverifikasi')
                                ->success()
                                ->sendToDatabase($record->audits()->latest()->first()->user)
                                ->seconds(5)
                                ->persistent()
                                ->send())
                            ->visible(function (Penduduk $record) use ($auth) {
                                $role = $auth->hasRole('Admin');
                                if ($role && $record->status_pengajuan->value == StatusPengajuanType::BELUM_DIVERIFIKASI->value) {
                                    return true;
                                }
                            }),
                        ActionsAction::make('Tinjau')
                            ->form([
                                TextInput::make('catatan')
                                    ->label('Catatan')
                                    ->required(),
                            ])
                            ->action(
                                function (Penduduk $record) {
                                    $record->update(['status_pengajuan' => StatusPengajuanType::TINJAU_ULANG->value]);
                                }
                            )
                            ->color('warning')->label('Tinjau Ulang')->icon('fas-circle-question')->iconSize(IconSize::Small)
                            ->requiresConfirmation()->after(fn(Penduduk $record, array $data) => Notification::make()
                                ->title('Penduduk ' . $record->nama_lengkap . ' perlu ditinjau ulang')
                                ->body('Catatan : ' . $data['catatan'])
                                ->danger()
                                ->sendToDatabase($record->audits()->latest()->first()->user)
                                ->seconds(10)
                                ->persistent()
                                ->send())
                            ->visible(function (Penduduk $record) use ($auth) {
                                $role = $auth->hasRole('Admin') || $auth->hasRole('Monitor Wilayah');
                                $pengajuan = $record->status_pengajuan->value;
                                if ($role && ($pengajuan == StatusPengajuanType::DIVERIFIKASI->value)) {
                                    return true;
                                }
                            }),

                        Tables\Actions\DeleteAction::make()->color('danger')->icon('fas-trash-alt')->iconSize(IconSize::Small),
                        Tables\Actions\ViewAction::make()->color('success')->icon('fas-eye')->iconSize(IconSize::Small),
                    ])->icon("fas-gears")->iconPosition('after')->color('success')->button()->label('Aksi'),
                ],
                position: ActionsPosition::AfterColumns
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Verifikasi')->label('Verifikasi')->icon('heroicon-m-pencil-square')->color('success')
                        ->action(
                            function (Collection $records) {
                                $records->each->update(['status_pengajuan' => StatusPengajuanType::DIVERIFIKASI->value]);
                            }
                        )
                        ->visible(
                            function () use ($auth) {
                                $auth->hasRole('Admin') ? true : false;
                            }
                        )
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->recordUrl(fn(Penduduk $record) => static::getUrl('edit', ['record' => $record->nik]))
            ->recordClasses(fn(Model $record) => empty($record->wilayah?->wilayah_nama) ? 'bg-red-100' : '')
            ->emptyStateActions([])
            ->deferLoading()
            ->striped();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        /** @var \App\Models\User */
        $auth = Filament::auth()->user();
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        ComponentsGroup::make([
                            ComponentsSection::make()
                                ->heading('Identitas Penduduk')
                                ->schema(
                                    [
                                        Split::make([
                                            ImageEntry::make('foto')
                                                ->visibility('private')
                                                ->hiddenLabel()
                                                ->defaultImageUrl(
                                                    fn(Penduduk $record) =>
                                                    strtolower($record->jenis_kelamin->value) === 'laki-laki' ? url('/images/user-man.png') : url('/images/user-woman.png')
                                                )->extraAttributes(['class' => 'justify-center'])
                                                ->size(240),
                                            ComponentsGroup::make()
                                                ->schema([
                                                    TextEntry::make('nik')
                                                        ->label('NIK')
                                                        ->weight(FontWeight::SemiBold)
                                                        ->color('primary')
                                                        ->inlineLabel()
                                                        ->copyable()
                                                        ->copyMessage('Telah Disalin!')
                                                        ->copyMessageDuration(1000),
                                                    TextEntry::make('nama_lengkap')
                                                        ->label('Nama Lengkap')
                                                        ->color('primary')
                                                        ->weight(FontWeight::SemiBold)
                                                        ->copyable()
                                                        ->inlineLabel()
                                                        ->copyMessage('Telah Disalin!')
                                                        ->copyMessageDuration(1000),
                                                    TextEntry::make('kk_id')
                                                        ->label('No. KK')
                                                        ->color('primary')
                                                        ->weight(FontWeight::SemiBold)
                                                        ->copyable()
                                                        ->inlineLabel()
                                                        ->copyMessage('Telah Disalin!')
                                                        ->copyMessageDuration(1000),
                                                    TextEntry::make('wilayah.wilayah_nama')
                                                        ->label('Wilayah')
                                                        ->color('primary')
                                                        ->inlineLabel()
                                                        ->weight(FontWeight::SemiBold)
                                                        ->copyable()
                                                        ->copyMessage('Telah Disalin!')
                                                        ->copyMessageDuration(1000),
                                                    TextEntry::make('jenis_identitas')
                                                        ->label('Jenis Identitas')
                                                        ->inlineLabel()
                                                        ->weight(FontWeight::SemiBold)
                                                        ->copyable()
                                                        ->copyMessage('Telah Disalin!')
                                                        ->copyMessageDuration(1000),
                                                    TextEntry::make('jenis_kelamin')
                                                        ->inlineLabel()
                                                        ->weight(FontWeight::SemiBold)
                                                        ->label('Jenis Kelamin'),
                                                ])
                                        ]),
                                        ComponentsSection::make('')
                                            ->schema([
                                                TextEntry::make('telepon')
                                                    ->label('Telepon')
                                                    ->inlineLabel()
                                                    ->weight(FontWeight::SemiBold)
                                                    ->copyable()
                                                    ->copyMessage('Telah Disalin!')
                                                    ->copyMessageDuration(1000),
                                                TextEntry::make('email')
                                                    ->label('Email')
                                                    ->inlineLabel()
                                                    ->weight(FontWeight::SemiBold)
                                                    ->copyable()
                                                    ->copyMessage('Telah Disalin!')
                                                    ->copyMessageDuration(1000),
                                            ])

                                    ]
                                )->columnSpan(['lg' => 2]),
                            ComponentsSection::make()
                                ->heading('Informasi Penduduk')
                                ->columns(2)
                                ->schema([
                                    TextEntry::make('tempat_lahir')
                                        ->inlineLabel()
                                        ->weight(FontWeight::SemiBold)
                                        ->label('Tempat Lahir'),
                                    TextEntry::make('tanggal_lahir')
                                        ->label('Tanggal Lahir')
                                        ->date(format: 'd F Y')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel(),
                                    TextEntry::make('pendidikan')
                                        ->label('Pendidikan')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('pekerjaan')
                                        ->label('Pekerjaan')
                                        ->inlineLabel()
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('status_perkawinan')
                                        ->label('Status Perkawinan')
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->inlineLabel()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('kewarganegaraan')
                                        ->label('Kewarganegaraan')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('agama')
                                        ->label('Agama')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('golongan_darah')
                                        ->label('Golongan Darah')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),

                                    TextEntry::make('status_dasar')
                                        ->label('Status Dasar')
                                        ->icon(false)
                                        ->inlineLabel()
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('status_hubungan')
                                        ->label('Status Hubungan')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('nama_ayah')
                                        ->label('Nama Ayah')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('nama_ibu')
                                        ->label('Nama Ibu')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('nik_ayah')
                                        ->label('NIK Ayah')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('nik_ibu')
                                        ->label('NIK Ibu')
                                        ->weight(FontWeight::SemiBold)
                                        ->inlineLabel()
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                ])->columnSpan(['lg' => 2]),
                        ])->columnSpan(['lg' => 2], ['sm' => 2]),

                        ComponentsGroup::make([
                            ComponentsSection::make('')
                                ->schema([
                                    TextEntry::make('created_at')
                                        ->label('Dibuat Pada')
                                        ->since(),
                                    TextEntry::make('updated_at')
                                        ->label('Diubah Pada')
                                        ->formatStateUsing(
                                            function (Penduduk $record) {
                                                // cari user rt dengan wilayah id penduduk ini
                                                if ($record->audits()->count() > 0) {
                                                    $latestAudit = $record->audits()->latest()->first();
                                                    $userName = $latestAudit->user->name;
                                                    $timeDiff = $record->updated_at->diffForHumans();

                                                    return $timeDiff . ' oleh ' . $userName;
                                                } else {
                                                    return 'Belum ada yang mengubah';
                                                }
                                            }
                                        ),
                                ]),
                            ComponentsSection::make('')
                                ->schema([
                                    TextEntry::make('alamat_sekarang')
                                        ->label('Alamat Sekarang')
                                        ->inlineLabel()
                                        ->weight(FontWeight::SemiBold)
                                        ->formatStateUsing(
                                            function ($state) {
                                                return ucwords(strtolower($state));
                                            }
                                        )
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('alamat_sebelumnya')
                                        ->label('Alamat Sebelumnya')
                                        ->inlineLabel()
                                        ->formatStateUsing(
                                            function ($state) {
                                                return ucwords(strtolower($state));
                                            }
                                        )
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    TextEntry::make('status_tempat_tinggal')
                                        ->placeholder('Belum Diketahui')
                                        ->label('Status Tempat Tinggal')
                                        ->inlineLabel()
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                ]),
                            ComponentsSection::make('')
                                ->schema([
                                    TextEntry::make('status_pengajuan')
                                        ->label('Keterangan')
                                        ->inlineLabel()
                                        ->badge()
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    IconEntry::make('is_nik_sementara')
                                        ->label('NIK Sementara')
                                        ->inlineLabel()
                                        ->trueColor('success')
                                        ->falseColor('danger')
                                        ->trueIcon('fas-check')
                                        ->falseIcon('fas-times')
                                        ->iconPosition(IconPosition::After),

                                    Actions::make([
                                        Action::make('Verifikasi')
                                            ->action(fn(Penduduk $record) => $record->update(['status_pengajuan' => StatusPengajuanType::DIVERIFIKASI->value]))
                                            ->color('success')->label('Verifikasi')->button()->icon('fas-check')->iconSize(IconSize::Small)
                                            ->requiresConfirmation()->after(fn(Penduduk $record) => Notification::make()
                                                ->title('Penduduk ' . $record->nama_lengkap . ' Berhasil di Perbarui')
                                                ->body($record->nama_lengkap . ' sudah diverifikasi')
                                                ->success()
                                                ->sendToDatabase($record->audits()->latest()->first()->user)
                                                ->seconds(5)
                                                ->persistent()
                                                ->send())
                                            ->visible(function (Penduduk $record) use ($auth) {
                                                $role = $auth->hasRole('Admin');
                                                if ($role && $record->status_pengajuan->value == StatusPengajuanType::BELUM_DIVERIFIKASI->value) {
                                                    return true;
                                                }
                                            }),
                                        Action::make('Tinjau')
                                            ->form([
                                                TextInput::make('catatan')
                                                    ->label('Catatan')
                                                    ->required(),
                                            ])
                                            ->action(
                                                function (Penduduk $record) {
                                                    $record->update(['status_pengajuan' => StatusPengajuanType::TINJAU_ULANG->value]);
                                                }
                                            )
                                            ->label('Tinjau Ulang')->color('warning')->icon('fas-circle-question')->iconSize(IconSize::Small)
                                            ->requiresConfirmation()
                                            ->after(fn(Penduduk $record, array $data) => Notification::make()
                                                ->title('Penduduk ' . $record->nama_lengkap . ' perlu ditinjau ulang')
                                                ->body('Catatan : ' . $data['catatan'])
                                                ->danger()
                                                ->sendToDatabase($record->audits()->latest()->first()->user)
                                                ->seconds(15)
                                                ->persistent()
                                                ->send())
                                            ->visible(function (Penduduk $record) use ($auth) {
                                                $role = $auth->hasRole('Admin') || $auth->hasRole('Monitor Wilayah');
                                                $pengajuan = $record->status_pengajuan->value;

                                                if ($role && ($pengajuan == StatusPengajuanType::DIVERIFIKASI->value)) {
                                                    return true;
                                                }
                                            }),

                                    ])
                                ])
                        ])->columnSpan(['lg' => 1], ['sm' => 2]),
                    ]),

            ]);
    }


    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenduduks::route('/'),
            'create' => Pages\CreatePenduduk::route('/create'),
            'view' => Pages\ViewPenduduk::route('/{record}'),
            'edit' => Pages\EditPenduduk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;

        return parent::getEloquentQuery()->byWilayah($authUser, $descendants);
    }

    public static function getWidgets(): array
    {
        return [
            PendudukOverview::class,
        ];
    }

    protected static function unchangedAuditNotification()
    {
        Notification::make()
            ->title(trans('filament-auditing::filament-auditing.notification.unchanged'))
            ->warning()
            ->send();
    }

    public static function getPendudukFormSchema(): array
    {
        return
            [
                ComponentsSplit::make([
                    Group::make()
                        ->schema([
                            Group::make()
                                ->schema([
                                    FileUpload::make('foto')
                                        ->hiddenLabel()
                                        ->alignCenter()
                                        ->getUploadedFileNameForStorageUsing(
                                            fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                ->prepend('gambar-penduduk-'),
                                        )
                                        ->disk('public')
                                        ->directory('penduduk')
                                        ->moveFiles()
                                        ->avatar()
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                                        ->panelAspectRatio('2:3')
                                        ->panelLayout('integrated')
                                        ->imagePreviewHeight('300')
                                        ->loadingIndicatorPosition('right')
                                        ->removeUploadedFileButtonPosition('right')
                                        ->uploadProgressIndicatorPosition('left')
                                        ->columnStart(['lg' => 1, 'md' => 1]),
                                    Section::make()
                                        ->schema([
                                            Checkbox::make('is_nik_sementara')
                                                ->label('NIK Sementara')
                                                ->live()
                                                ->afterStateUpdated(fn(Get $get, Set $set) => $set('nik', $get('is_nik_sementara') ? (string) rand(1000000000000000, 9999999999999999) : ''))
                                                ->default(false)
                                                ->inline(),
                                            TextInput::make('nik')
                                                ->label('NIK')
                                                ->numeric()
                                                ->minLength(16)
                                                ->reactive()
                                                ->unique(Penduduk::class, 'nik')
                                                ->dehydrated()
                                                ->hint(fn(Get $get) => $get('is_nik_sementara') ? new HtmlString('<span class="text-red-500">NIK Sementara</span>') : '')
                                                ->placeholder('Masukkan NIK')
                                                ->required(),
                                            Select::make('jenis_identitas')
                                                ->label('Jenis Identitas')
                                                ->placeholder('Pilih Jenis Identitas')
                                                ->options([
                                                    'KTP' => 'KTP',
                                                    'E-KTP' => 'E-KTP',
                                                    'KIA' => 'KIA',
                                                    'BELUM WAJIB' => 'BELUM WAJIB'
                                                ])
                                                ->required(),
                                            TextInput::make('nama_lengkap')
                                                ->label('Nama Lengkap')
                                                ->placeholder('Masukkan Nama Lengkap')
                                                ->required(),
                                            Select::make('status_penduduk')
                                                ->label('Status Penduduk')
                                                ->placeholder('Pilih Status Penduduk')
                                                ->options([
                                                    'TETAP' => 'Tetap',
                                                    'SEMENTARA' => 'Sementara',
                                                ])
                                                ->required(),

                                        ])->columnStart([
                                            'lg' => 2,
                                            'md' => 1,
                                            'sm' => 1,
                                        ])->columnSpan([
                                            'lg' => 2,
                                            'md' => 3,
                                            'sm' => 3,
                                        ])
                                ])->columns(
                                    [
                                        'lg' => 3,
                                        'xl' => 3,
                                        'md' => 1,
                                        'sm' => 1,
                                    ]
                                )->columnSpanFull(),

                        ])->columns(2),
                ]),
                Fieldset::make()
                    ->label('Data Diri')
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->columns(2)
                    ->schema([
                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->placeholder('Masukkan Tempat Lahir')
                            ->required(),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->placeholder('Pilih Tanggal Lahir')
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->placeholder('Pilih Jenis Kelamin')
                            ->options(JenisKelaminType::class)
                            ->required(),
                        Select::make('agama')
                            ->label('Agama')
                            ->placeholder('Pilih Agama')
                            ->options(AgamaType::class)
                            ->required(),
                        Select::make('golongan_darah')
                            ->placeholder('Pilih Golongan Darah')
                            ->label('Golongan Darah')
                            ->options(GolonganDarahType::class)
                            ->required(),
                        Select::make('etnis_suku')
                            ->placeholder('Pilih Etnis Suku')
                            ->label('Etnis Suku')
                            ->options(EtnisSukuType::class),
                        Select::make('kewarganegaraan')
                            ->placeholder('Pilih Kewarganegaraan')
                            ->label('Kewarganegaraan')
                            ->options(KewarganegaraanType::class)
                            ->required(),
                        Select::make('status_tempat_tinggal')
                            ->placeholder('Pilih Status Tempat Tinggal')
                            ->label('Status Tempat Tinggal')
                            ->options(StatusTempatTinggalType::class),

                    ]),
                Fieldset::make('Status Hubungan dalam Keluaga dan Perkawinan')
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->label('Hubungan Keluarga dan Perkawinan')
                    ->schema([
                        Select::make('status_hubungan')
                            ->label('Status Hubungan dalam Keluarga')
                            ->placeholder('Pilih Status Hubungan dalam Keluarga')
                            ->options(
                                fn(Select $component) => $component->getContainer()->getParentComponent()->getContainer()->getParentComponent()->getKey() === 'kepalakeluarga'
                                    ? [StatusHubunganType::KEPALA_KELUARGA->value => StatusHubunganType::KEPALA_KELUARGA->getLabel()]
                                    : collect(StatusHubunganType::cases())
                                    ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                    ->except(StatusHubunganType::KEPALA_KELUARGA->value)
                                    ->toArray()
                            )
                            ->live()
                            ->required(),
                        Select::make('status_perkawinan')
                            ->placeholder('Pilih Status Perkawinan')
                            ->label('Status Perkawinan')
                            ->options(PerkawinanType::class)
                            ->live()
                            ->afterStateUpdated(
                                fn(Select $component) => $component
                                    ->getContainer()
                                    ->getComponent('statusPerkawinanFields')
                                    ->getChildComponentContainer()
                                    ->fill()
                            )
                            ->required(),
                        FormsGrid::make(1)
                            ->schema(fn(Get $get): array => match ($get('status_perkawinan')) {
                                'KAWIN', 'KAWIN TERCATAT', 'KAWIN BELUM TERCATAT' => [
                                    DatePicker::make('tgl_perkawinan')
                                        ->label('Tanggal Perkawinan')
                                        ->placeholder('Pilih Tanggal Perkawinan')
                                        ->required()
                                        ->native(false)
                                ],
                                'CERAI', 'CERAI BELUM TERCATAT', 'CERAI HIDUP', 'CERAI HIDUP BELUM TERCATAT', 'CERAI HIDUP TERCATAT', 'CERAI MATI', 'CERAI TERCATAT' => [
                                    DatePicker::make('tgl_perceraian')
                                        ->placeholder('Pilih Tanggal Perceraian')
                                        ->label('Tanggal Perceraian')
                                        ->required()
                                        ->native(false)
                                ],
                                default => [],
                            })
                            ->key('statusPerkawinanFields'),

                    ]),
                Fieldset::make('Orang Tua')
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->label('Orang Tua')
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->placeholder('Masukkan Nama Ayah')
                            ->label('Nama Ayah')
                            ->required(),
                        TextInput::make('nik_ayah')
                            ->label('NIK Ayah'),
                        TextInput::make('nama_ibu')
                            ->placeholder('Masukkan Nama Ibu')
                            ->label('Nama Ibu')
                            ->required(),
                        TextInput::make('nik_ibu')
                            ->label('NIK Ibu'),

                    ]),

                Fieldset::make('Pendidikan dan Pekerjaan')
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->label('Pendidikan dan Pekerjaan')
                    ->schema([
                        Select::make('pendidikan')
                            ->placeholder('Pilih Pendidikan')
                            ->label('Pendidikan')
                            ->options(PendidikanType::class)
                            ->required(),
                        Select::make('pekerjaan')
                            ->placeholder('Pilih Pekerjaan')
                            ->label('Pekerjaan')
                            ->options(PekerjaanType::class)
                            ->required(),

                    ]),
                Fieldset::make('Alamat dan Kontak')
                    ->label('Alamat dan Kontak')
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->schema([
                        Textarea::make('alamat_sekarang')
                            ->label('Alamat Sekarang')
                            ->autosize()
                            ->required(),
                        Textarea::make('alamat_sebelumnya')
                            ->label('Alamat Sebelumnya')
                            ->autosize()
                            ->required(),
                        TextInput::make('telepon')
                            ->tel()
                            ->label('Telepon'),
                        TextInput::make('email')
                            ->email()
                            ->label('Email'),
                    ])->columns(2),
                Fieldset::make('Kesehatan Anak dan Kelahiran')
                    ->label('Kesehatan Anak')
                    ->reactive()
                    ->hidden(
                        fn(Get $get) => (($get('status_hubungan') !== StatusHubunganType::ANAK->value) &&
                            ($get('status_hubungan') !== StatusHubunganType::CUCU->value) &&
                            ($get('status_hubungan') !== StatusHubunganType::FAMILI_LAIN->value)) ||
                            ($get('tanggal_lahir') < now()->subYears(5))
                    )
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->schema([
                        TextInput::make('anak_ke')
                            ->placeholder('Masukkan Anak Ke')
                            ->label('Anak Ke')
                            ->numeric(),
                        Select::make('jenis_lahir')
                            ->options([
                                'Tunggal' => 'Tunggal',
                                'Kembar 2' => 'Kembar 2',
                                'Kembar 3' => 'Kembar 3',
                                'Kembar 4' => 'Kembar 4',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->placeholder('Pilih Jenis Kelahiran')
                            ->label('Jenis Kelahiran'),

                        Select::make('penolong_lahir')
                            ->placeholder('Masukkan Penolong Kelahiran')
                            ->options([
                                'Dokter' => 'Dokter',
                                'Bidan' => 'Bidan',
                                'Perawat' => 'Perawat',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->label('Penolong Kelahiran'),
                        TextInput::make('berat_lahir')
                            ->placeholder('Masukkan Berat Lahir')
                            ->label('Berat Lahir')
                            ->inputMode('decimal')
                            ->numeric(),
                        TextInput::make('tinggi_lahir')
                            ->placeholder('Masukkan Tinggi Lahir')
                            ->label('Tinggi Lahir')
                            ->inputMode('decimal')
                            ->numeric(),
                    ])->columns(2),
                Fieldset::make('Informasi Tambahan')
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900'])
                    ->schema([
                        DatePicker::make('tanggal_dinamika')
                            ->label('Tanggal Dinamika')
                            ->placeholder('Pilih Tanggal Dinamika')
                            ->hint('( Tanggal Pindah Masuk/Kelahiran )')
                            ->required(),
                        DatePicker::make('tanggal_lapor')
                            ->label('Tanggal Lapor')
                            ->placeholder('Pilih Tanggal Lapor')
                            ->hint('( Tanggal Lapor Pindah Masuk/Kelahiran )')
                            ->required(),
                        Textarea::make('catatan_dinamika')
                            ->label('Catatan Dinamika')
                            ->autosize()
                            ->placeholder('Masukkan Catatan Dinamika (Opsional)')
                            ->columnSpanFull()
                            ->required(),
                        Select::make('bantuan_sasaran')
                            ->label('Sasaran Bantuan')
                            ->live()
                            ->options(
                                function (Select $component) {
                                    $key = $component->getContainer()->getParentComponent()
                                        ->getContainer()->getParentComponent()->getKey();
                                    $key === 'kepalakeluarga'
                                        ? $cases = ['Keluarga' => 'Keluarga']
                                        : $cases = ['Penduduk' => 'Penduduk'];

                                    return $cases;
                                }
                            ),
                        Select::make('bantuans')
                            ->reactive()
                            ->native(false)
                            ->preload()
                            ->options(
                                function (Get $get) {
                                    $sasaran = $get('bantuan_sasaran');
                                    return ($sasaran === 'Keluarga')
                                        ? Bantuan::where('bantuan_sasaran', 'Keluarga')
                                        ->pluck('bantuan_program', 'bantuan_id')
                                        : Bantuan::where('bantuan_sasaran', 'Penduduk')
                                        ->pluck('bantuan_program', 'bantuan_id');
                                }
                            )
                            ->multiple()
                    ])->columns(2),
            ];
    }
}
