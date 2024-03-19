<?php

namespace App\Filament\Clusters\Kependudukan\Resources;

use App\Enums\Kependudukan\{AgamaType, EtnisSukuType, GolonganDarahType, JenisKelaminType, KewarganegaraanType, PendidikanType, PekerjaanType, StatusPengajuanType, PerkawinanType, StatusDasarType, StatusHubunganType, StatusTempatTinggalType};
use App\Facades\Deskel;
use App\Filament\Clusters\Kependudukan\HalamanKependudukan;
use App\Filament\Clusters\Kependudukan\Resources\PendudukResource\Pages;
use App\Filament\Clusters\Kependudukan\Resources\PendudukResource\Widgets\PendudukOverview;
use App\Models\{Bantuan, Kepindahan, Kematian, Penduduk, Dinamika};
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Coolsam\FilamentFlatpickr\Enums\FlatpickrTheme;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Form;
use Filament\Forms\Components\{Actions\Action as FormsAction, Checkbox, Component, Group, Section, Select, TextInput, DatePicker, DateTimePicker, Fieldset, FileUpload, Grid as FormsGrid, Hidden, Placeholder, Split as ComponentsSplit, Textarea, TimePicker, Wizard};
use Filament\Forms\Components\Actions\Action as ComponentsActionsAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\{Actions, Grid, Group as ComponentsGroup, Section as ComponentsSection, Split, TextEntry};
use Filament\Infolists\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action as ActionsAction, ActionGroup, BulkAction};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

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
        $cek = Deskel::getFacadeRoot()->deskel_status;
        if ($cek == true) {
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
                                    TextInput::make('nik')
                                        ->label('NIK')
                                        ->unique(ignoreRecord: true)
                                        ->live()
                                        ->afterStateUpdated(function (HasForms $livewire, TextInput $component) {
                                            $livewire->validateOnly($component->getStatePath());
                                        })
                                        ->required(),
                                    TextInput::make('nama_lengkap')
                                        ->label('Nama Lengkap')
                                        ->required(),
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
                                                    ->content(fn (Penduduk $record): ?string => ($record->created_at?->diffForHumans()))
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
                                        )->hidden(fn (?Penduduk $record) => $record === null),
                                    // Section::make()
                                    //     ->heading('Data Lainnya')
                                    //     ->description('Silahkan Isi Ada')
                                    //     ->schema([
                                    //         Select::make('kesehatan')
                                    //             ->preload()
                                    //             ->relationship('kesehatan', 'kes_id')
                                    //             ->multiple()

                                    //             ->searchingMessage('Mencari Jaminan Kesehatan')
                                    //             ->createOptionForm(
                                    //                 [
                                    //                     TextInput::make('kes_id')
                                    //                         ->label('Jaminan Kesehatan')
                                    //                 ]
                                    //             ),
                                    //         Select::make('bantuan')
                                    //             ->label('Bantuan')
                                    //     ])->collapsible(),
                                ]),
                            Section::make()
                                ->heading('Status Tempat Tinggal')
                                ->description('Keterangan Status Tempat Tinggal')
                                ->schema(
                                    [
                                        Select::make('status dasar')
                                            ->label('Status Dasar')
                                            ->options(StatusDasarType::class)
                                            ->required(),
                                        TextInput::make('alamat')
                                            ->label('Alamat')
                                            ->required(),
                                    ]
                                ),
                        ])->columnSpan(['lg' => 1]),
                ]
            )->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
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
                TextColumn::make('alamatKK')
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
                //
            ])
            ->actions(
                [
                    Tables\Actions\ViewAction::make()->button()->color('primary')->iconSize(IconSize::Small),


                    ActionsAction::make('Batalkan')->action(
                        function (Penduduk $record) {

                            static::restoreAuditSelected($record);
                            $record->update(['status_pengajuan' => 'SELESAI']);
                        }
                    )
                        ->color('danger')->label(
                            'Batalkan'
                        )->button()
                        ->requiresConfirmation()->after(fn (Penduduk $record) =>
                        Notification::make()
                            ->title('Penduduk ' . $record->nama_lengkap . ' Berhasil di Perbarui')
                            ->body($record->nama_lengkap . ' dibatalkan , data penduduk akan dikembalikan ke sebelumnya. Silahkan periksa kembali data penduduk')
                            ->danger()
                            ->sendToDatabase(
                                $record->audits()->latest()->first()->user
                            )
                            ->seconds(5)
                            ->persistent()
                            ->send())
                        ->visible(function (Penduduk $record) {

                            $roles = auth()->user()->roles->pluck('name');
                            $pengajuan = $record->status_pengajuan->value;
                            foreach ($roles as $role) {
                                if ($role == 'Monitor Wilayah' && ($pengajuan == 'DALAM PROSES')) {
                                    return true;
                                }
                            }
                        }),

                    ActionGroup::make([
                        ActionsAction::make('Ubah Status Dasar')
                            ->icon('fas-pen-to-square')
                            ->color('success')
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
                                        fn (Select $component) => $component
                                            ->getContainer()
                                            ->getComponent('statusDasarFields')
                                            ->getChildComponentContainer()
                                            ->fill()
                                    )
                                    ->required(),
                                FormsGrid::make(1)
                                    ->schema(fn (Get $get): array => match ($get('status_dasar')) {
                                        'MENINGGAL' => [
                                            TextInput::make('tempat_kematian')
                                                ->label('Tempat Meninggal')
                                                ->required(),
                                            Flatpickr::make('waktu_kematian')
                                                ->label('Waktu Meninggal')
                                                ->time()
                                                ->animate()
                                                ->allowInput(true)
                                                ->enableSeconds(true)
                                                ->use24hr(true)
                                                ->clickOpens(true)
                                                ->theme(FlatpickrTheme::MATERIAL_BLUE),
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
                        ActionsAction::make('Tinjau')
                            ->form([
                                TextInput::make('catatan')
                                    ->label('Catatan')
                                    ->required(),
                            ])
                            ->action(
                                function (Penduduk $record) {
                                    $record->update(['status_pengajuan' => 'DIBATALKAN']);
                                }
                            )
                            ->color('danger')->label(
                                'Tinjau Ulang'
                            )->icon('fas-circle-question')
                            ->requiresConfirmation()->after(fn (Penduduk $record, array $data) => Notification::make()
                                ->title('Penduduk ' . $record->nama_lengkap . ' perlu ditinjau ulang')
                                ->body('Catatan : ' . $data['catatan'])
                                ->danger()
                                ->sendToDatabase(
                                    $record->audits()->latest()->first()->user
                                )
                                ->seconds(5)
                                ->persistent()
                                ->send())
                            ->visible(function (Penduduk $record) {
                                $roles = auth()->user()->roles->pluck('name');
                                $pengajuan = $record->status_pengajuan->value;
                                foreach ($roles as $role) {
                                    if ($role == 'admin' && ($pengajuan == 'DALAM PROSES' || $pengajuan == 'SELESAI')) {
                                        return true;
                                    }
                                }
                            }),

                        Tables\Actions\DeleteAction::make(),
                        Tables\Actions\ViewAction::make()->color('primary')->iconSize(IconSize::Small),
                        Tables\Actions\EditAction::make()->color('info')->iconSize(IconSize::Small),
                    ])->icon("fas-gears")->iconPosition('after')->color('success')->iconButton()->label('Aksi'),
                ],
                position: ActionsPosition::AfterColumns
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Verifikasi')->label('Verifikasi')->icon('heroicon-m-pencil-square')->color('success')
                        ->action(
                            function (Collection $records) {
                                $records->each->update(['status_pengajuan' => 'SELESAI']);
                            }
                        )
                        ->visible(
                            function () {
                                $roles = auth()->user()->roles->pluck('name');
                                if ($roles->contains('super_admin')) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }

                        )->deselectRecordsAfterCompletion()->requiresConfirmation(),
                ]),
                ExportBulkAction::make()
            ])
            ->emptyStateActions([])
            ->deferLoading()
            ->striped();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Grid::make(3)
                    ->schema([
                        ComponentsGroup::make([
                            ComponentsSection::make()
                                ->heading('Informasi Penduduk')
                                ->description('Berikut adalah informasi penduduk')
                                ->schema(
                                    [
                                        Split::make([
                                            Grid::make(2)
                                                ->schema([
                                                    ComponentsGroup::make([
                                                        TextEntry::make('nik')
                                                            ->label('NIK')
                                                            ->weight(FontWeight::Bold)
                                                            ->inlineLabel()
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('nama_lengkap')
                                                            ->label('Nama Lengkap')
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->inlineLabel()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),

                                                        TextEntry::make('alamat_sekarang')
                                                            ->label('Alamat')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('pendidikan')
                                                            ->label('Pendidikan')
                                                            ->weight(FontWeight::Bold)
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)

                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('status_perkawinan')
                                                            ->label('Status Perkawinan')
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->inlineLabel()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('pekerjaan')
                                                            ->label('Pekerjaan')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                    ]),
                                                    ComponentsGroup::make([
                                                        TextEntry::make('jenis_identitas')
                                                            ->label('Jenis Identitas')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('kewarganegaraan')
                                                            ->label('Kewarganegaraan')
                                                            ->weight(FontWeight::Bold)
                                                            ->inlineLabel()
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('jenis_kelamin')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)

                                                            ->label('Jenis Kelamin'),
                                                        TextEntry::make('tempat_lahir')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)

                                                            ->label('Tempat Lahir'),
                                                        TextEntry::make('tanggal_lahir')
                                                            ->label('Tanggal Lahir')
                                                            ->weight(FontWeight::Bold)
                                                            ->inlineLabel(),
                                                        TextEntry::make('agama')
                                                            ->label('Agama')
                                                            ->weight(FontWeight::Bold)
                                                            ->inlineLabel()

                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('status_dasar')
                                                            ->label('Status Dasar')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                    ])
                                                ])
                                        ]),

                                    ]
                                )->columnSpan(['lg' => 2]),
                            // ComponentsSection::make()
                            //     ->heading('Informasi Tambahan')
                            //     ->description('Berikut adalah informasi tambahan dari penduduk')
                            //     ->schema(
                            //         [
                            //             Split::make([
                            //                 Grid::make(2)
                            //                     ->schema([
                            //                         ComponentsGroup::make([
                            //                             TextEntry::make('kesehatan.kesehatan_jaminan')
                            //                                 ->label('Kesehatan')
                            //                                 ->weight(FontWeight::Bold)
                            //                                 ->badge()
                            //                                 ->copyable()
                            //                                 ->inlineLabel()
                            //                                 ->copyMessage('Telah Disalin!')
                            //                                 ->copyMessageDuration(1000),
                            //                             TextEntry::make('bantuan')
                            //                                 ->label('Bantuan')
                            //                                 ->weight(FontWeight::Bold)
                            //                                 ->copyable()
                            //                                 ->inlineLabel()
                            //                                 ->copyMessage('Telah Disalin!')
                            //                                 ->copyMessageDuration(1000),
                            //                         ]),

                            //                     ])
                            //             ]),
                            //         ]
                            //     )->columnSpan(['lg' => 2]),
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
                                    TextEntry::make('status_pengajuan')
                                        ->label('Pengajuan')
                                        ->inlineLabel()
                                        ->badge()
                                        ->weight(FontWeight::Bold)
                                        ->copyable()
                                        ->copyMessage('Telah Disalin!')
                                        ->copyMessageDuration(1000),
                                    Actions::make([
                                        Action::make('verifikasi')
                                            ->action(
                                                fn (Penduduk $record) => $record->update(['status_pengajuan' => 'SELESAI']),
                                            )->color('success')->label(
                                                'Verifikasi'
                                            )->button()
                                            ->requiresConfirmation()->after(fn (Penduduk $record) => Notification::make()
                                                ->title('Penduduk ' . $record->nama_lengkap . ' Berhasil di Perbarui')
                                                ->body($record->nama_lengkap . ' sudah diverifikasi')
                                                ->success()
                                                ->sendToDatabase(
                                                    $record->audits()->latest()->first()->user
                                                )
                                                ->seconds(5)
                                                ->persistent()
                                                ->send())->visible(function (Penduduk $record) {
                                                $roles = auth()->user()->roles->pluck('name');
                                                if ($roles->contains('admin') && $record->status_pengajuan->value == 'DALAM PROSES') {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            }),
                                        Action::make('Batalkan')->action(
                                            function (Penduduk $record) {

                                                static::restoreAuditSelected($record);
                                                $record->update(['status_pengajuan' => 'SELESAI']);
                                            }
                                        )
                                            ->color('danger')->label(
                                                'Batalkan'
                                            )->button()
                                            ->requiresConfirmation()->after(fn (Penduduk $record) => Notification::make()
                                                ->title('Penduduk ' . $record->nama_lengkap . ' Berhasil di Perbarui')
                                                ->body($record->nama_lengkap . ' dibatalkan , data penduduk akan dikembalikan ke sebelumnya. Silahkan periksa kembali data penduduk')
                                                ->danger()
                                                ->sendToDatabase(
                                                    // kirim ke rt yang dengan wilayah id penduduk ini
                                                    $record->audits()->latest()->first()->user
                                                )
                                                ->seconds(5)
                                                ->persistent()
                                                ->send())
                                            ->visible(function (Penduduk $record) {
                                                $roles = auth()->user()->roles->pluck('name');
                                                $pengajuan = $record->status_pengajuan->value;
                                                foreach ($roles as $role) {
                                                    if ($role == 'Monitor Wilayah' && ($pengajuan == 'DALAM PROSES')) {
                                                        return true;
                                                    }
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
                                                    $record->update(['status_pengajuan' => 'DIBATALKAN']);
                                                }
                                            )
                                            ->color('danger')->label(
                                                'Tinjau Ulang'
                                            )->button()
                                            ->requiresConfirmation()->after(fn (Penduduk $record, array $data) => Notification::make()
                                                ->title('Penduduk ' . $record->nama_lengkap . ' perlu ditinjau ulang')
                                                ->body('Catatan : ' . $data['catatan'])
                                                ->danger()
                                                ->sendToDatabase(
                                                    $record->audits()->latest()->first()->user
                                                )
                                                ->seconds(5)
                                                ->persistent()
                                                ->send())
                                            ->visible(function (Penduduk $record) {
                                                $roles = auth()->user()->roles->pluck('name');
                                                $pengajuan = $record->status_pengajuan->value;
                                                foreach ($roles as $role) {
                                                    if ($role == 'super_admin' && ($pengajuan == 'DALAM PROSES' || $pengajuan == 'SELESAI')) {
                                                        return true;
                                                    }
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
    public function getTableBulkActions()
    {
        return  [
            ExportBulkAction::make(),

        ];
    }

    // public static function getEloquentQuery(): Builder
    // {

    //     $wilayah = auth()->user()->wilayah_id;

    //     if (empty($wilayah)) {
    //         return parent::getEloquentQuery();
    //     } else {
    //         return parent::getEloquentQuery()->byWilayah($wilayah);
    //     }

    //     ($roles = auth()->user()->roles->pluck('name'));

    //     if ($roles->contains('RT')) {
    //         $wilayahId = auth()->user()->wilayahRoles->pluck('wilayah.wilayah_id')->first();
    //         $queryRT = parent::getEloquentQuery()->whereHas('kartuKeluarga', function ($query) use ($wilayahId) {
    //             $query->where('wilayah_id', $wilayahId);
    //         });
    //     } elseif ($roles->contains('RW')) {
    //         $wilayahId = auth()->user()->wilayahRoles->pluck('wilayah.wilayah_id');
    //         $queryRW = parent::getEloquentQuery()->whereHas('kartuKeluarga', function ($query) use ($wilayahId) {
    //             $query->whereIn('wilayah_id', $wilayahId);
    //         });
    //     } else {
    //         $queryAdmin = parent::getEloquentQuery();
    //     }

    //     return $queryRT ?? $queryRW ?? $queryAdmin;
    // }

    public static function getWidgets(): array
    {
        return [
            PendudukOverview::class,
        ];
    }

    protected static function restoreAuditSelected($audit)
    {
        $oldvalues = $audit->audits()->latest()->first()->old_values;

        Arr::pull($oldvalues, 'id');

        if (is_array($oldvalues)) {

            foreach ($oldvalues as $key => $item) {
                $decode = json_decode($item);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $oldvalues[$key] = $decode;
                }
            }
            $audit->update($oldvalues);

            self::restoredAuditNotification();
        }
    }


    protected static function restoredAuditNotification()
    {
        Notification::make()
            ->title('Data Berhasil di Perbarui')
            ->success()
            ->send();
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
                                        ->extraAttributes(
                                            ['class' => 'mt-1',]
                                        )
                                        ->extraInputAttributes(
                                            ['class' => 'fi-pond-ta']
                                        )
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                ->prepend('gambar-penduduk-'),
                                        )
                                        ->disk('public')
                                        ->directory('penduduk')
                                        ->avatar()
                                        ->image()
                                        ->imageEditor()
                                        ->imageEditorAspectRatios([
                                            '9:16',
                                            '3:4',
                                            '1:1',
                                        ])
                                        ->imagePreviewHeight('300')
                                        ->loadingIndicatorPosition('right')
                                        ->panelAspectRatio('3.5:7')
                                        ->alignCenter()
                                        ->panelLayout('integrated')
                                        ->removeUploadedFileButtonPosition('right')
                                        ->uploadProgressIndicatorPosition('left')
                                        ->columnStart([
                                            'lg' => 1,
                                            'md' => 1,
                                        ]),
                                    Section::make()
                                        ->schema([
                                            Checkbox::make('is_nik_sementara')
                                                ->label('NIK Sementara')
                                                ->live()
                                                ->afterStateUpdated(
                                                    function (Get $get, Set $set) {
                                                        if ($get('is_nik_sementara') === true) {
                                                            $set('nik', (string) rand(1000000000000000, 9999999999999999));
                                                        } else {
                                                            $set('nik', '');
                                                        }
                                                    }
                                                )
                                                ->default(false)
                                                ->inline(),
                                            TextInput::make('nik')
                                                ->label('NIK')
                                                ->numeric()
                                                ->minLength(16)
                                                ->reactive()
                                                ->unique(Penduduk::class, 'nik')
                                                ->dehydrated()
                                                ->hint(function (Get $get) {
                                                    if ($get('is_nik_sementara') === true) {
                                                        return new HtmlString('<span class="text-red-500">NIK Sementara</span>');
                                                    } else {
                                                        return '';
                                                    }
                                                })

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
                                                    'Tetap' => 'Tetap',
                                                    'Sementara' => 'Sementara',
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
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'

                    ])
                    ->columns(2)
                    ->schema([
                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->placeholder('Masukkan Tempat Lahir')
                            ->required(),
                        Flatpickr::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->placeholder('Pilih Tanggal Lahir')
                            ->animate()
                            ->allowInput(true)
                            ->clickOpens(true)
                            ->theme(FlatpickrTheme::MATERIAL_BLUE)
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
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'
                    ])
                    ->label('Hubungan Keluarga dan Perkawinan')
                    ->schema([
                        Select::make('status_hubungan')
                            ->label('Status Hubungan dalam Keluarga')
                            ->placeholder('Pilih Status Hubungan dalam Keluarga')
                            ->options(
                                function (Select $component) {
                                    $key = $component->getContainer()->getParentComponent()->getContainer()->getParentComponent()->getKey();
                                    $allCases = collect(StatusHubunganType::cases());
                                    $cases = [];
                                    if ($key === 'kepalakeluarga') {
                                        $cases[StatusHubunganType::KEPALA_KELUARGA->value] = StatusHubunganType::KEPALA_KELUARGA->getLabel();
                                    } else {
                                        $cases = $allCases->mapWithKeys(
                                            fn ($case) => [$case->value => $case->getLabel()]
                                        )->except(
                                            StatusHubunganType::KEPALA_KELUARGA->value
                                        );
                                    }

                                    return $cases;
                                }
                            )
                            ->live()
                            ->required(),
                        Select::make('status_perkawinan')
                            ->placeholder('Pilih Status Perkawinan')
                            ->label('Status Perkawinan')
                            ->options(PerkawinanType::class)
                            ->live()
                            ->afterStateUpdated(
                                fn (Select $component) => $component
                                    ->getContainer()
                                    ->getComponent('statusPerkawinanFields')
                                    ->getChildComponentContainer()
                                    ->fill()
                            )
                            ->required(),
                        FormsGrid::make(1)
                            ->schema(fn (Get $get): array => match ($get('status_perkawinan')) {
                                'KAWIN', 'KAWIN TERCATAT', 'KAWIN BELUM TERCATAT' => [
                                    Flatpickr::make('tgl_perkawinan')
                                        ->label('Tanggal Perkawinan')
                                        ->placeholder('Pilih Tanggal Perkawinan')
                                        ->animate()
                                        ->allowInput(true)
                                        ->clickOpens(true)
                                        ->theme(FlatpickrTheme::MATERIAL_BLUE),
                                ],
                                'CERAI', 'CERAI BELUM TERCATAT', 'CERAI HIDUP', 'CERAI HIDUP BELUM TERCATAT', 'CERAI HIDUP TERCATAT', 'CERAI MATI', 'CERAI TERCATAT' => [
                                    Flatpickr::make('tgl_perceraian')
                                        ->placeholder('Pilih Tanggal Perceraian')
                                        ->label('Tanggal Perceraian')
                                        ->animate()
                                        ->allowInput(true)
                                        ->clickOpens(true)
                                        ->theme(FlatpickrTheme::MATERIAL_BLUE),
                                ],
                                default => [],
                            })
                            ->key('statusPerkawinanFields'),

                    ]),
                Fieldset::make('Orang Tua')
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'
                    ])
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
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'

                    ])
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
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'

                    ])
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
                        fn (Get $get) => (($get('status_hubungan') !== StatusHubunganType::ANAK->value) &&
                            ($get('status_hubungan') !== StatusHubunganType::CUCU->value) &&
                            ($get('status_hubungan') !== StatusHubunganType::FAMILI_LAIN->value)) ||
                            ($get('tanggal_lahir') < now()->subYears(5))
                    )
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'

                    ])
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
                    ->extraAttributes([
                        'class' => 'bg-white dark:bg-gray-900'
                    ])
                    ->schema([
                        Flatpickr::make('tanggal_dinamika')
                            ->label('Tanggal Dinamika')
                            ->placeholder('Pilih Tanggal Dinamika')
                            ->hint('( Tanggal Dinamika Pindah Masuk/Kelahiran )')
                            ->animate()
                            ->allowInput(true)
                            ->clickOpens(true)
                            ->required()
                            ->theme(FlatpickrTheme::MATERIAL_BLUE),
                        Flatpickr::make('tanggal_lapor')
                            ->label('Tanggal Lapor')
                            ->placeholder('Pilih Tanggal Lapor')
                            ->hint('( Tanggal Dinamika Lapor Masuk/Kelahiran )')
                            ->animate()
                            ->allowInput(true)
                            ->clickOpens(true)
                            ->required()
                            ->theme(FlatpickrTheme::MATERIAL_BLUE),
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
                        // ->createOptionForm(
                        //     [
                        //         TextInput::make('jenis_bantuan')
                        //             ->label('Jaminan Kesehatan'),
                        //         TextInput::make('keterangan_bantuan')
                        //             ->label('Keterangan Bantuan'),
                        //         DateTimePicker::make('tanggal_bantuan')
                        //             ->label('tanggal_bantuan')
                        //     ]
                        // )
                        // ->createOptionUsing(
                        //     function (array $data) {
                        //         return Bantuan::create([
                        //             'jenis_bantuan' => $data['jenis_bantuan'],
                        //             'keterangan_bantuan' => $data['keterangan_bantuan'],
                        //             'tanggal_bantuan' => $data['tanggal_bantuan'],
                        //         ]);
                        //     }
                        // ),

                    ])->columns(2),


            ];
    }
}
