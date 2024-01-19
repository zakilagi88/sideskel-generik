<?php

namespace App\Filament\Resources;

use App\Enum\Penduduk\{Agama, EtnisSuku, GolonganDarah, JenisKelamin, Kewarganegaraan, Pekerjaan, Pendidikan, Pengajuan, Perkawinan, Status, StatusHubungan, StatusTempatTinggal};
use App\Filament\Resources\PendudukResource\Pages;
use App\Filament\Resources\PendudukResource\Widgets\PendudukOverview;
use App\Models\Bantuan;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Form;
use Filament\Forms\Components\{Component, Group, Section, Select, TextInput, DatePicker, DateTimePicker, Fieldset, Placeholder, Textarea, Wizard};
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Resource;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\{Actions, Grid, Group as ComponentsGroup, Section as ComponentsSection, Split, TextEntry};
use Filament\Infolists\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action as ActionsAction, ActionGroup, BulkAction};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportNavigate\ParentComponent;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    protected static ?string $navigationIcon = 'fas-people-group';

    protected static ?string $navigationLabel = 'Data Penduduk';

    protected static ?string $slug = 'penduduk';


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
                                    Select::make('status_perkawinan')
                                        ->label('Status Perkawinan')
                                        ->options(Perkawinan::class),
                                    Select::make('pekerjaan')
                                        ->label('Pekerjaan')
                                        ->options(Pekerjaan::class)
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
                                    Section::make()
                                        ->heading('Data Lainnya')
                                        ->description('Silahkan Isi Ada')
                                        ->schema([
                                            Select::make('kesehatan')
                                                ->preload()
                                                ->relationship('kesehatan', 'kes_id')
                                                ->multiple()

                                                ->searchingMessage('Mencari Jaminan Kesehatan')
                                                ->createOptionForm(
                                                    [
                                                        TextInput::make('kes_id')
                                                            ->label('Jaminan Kesehatan')
                                                    ]
                                                ),
                                            Select::make('bantuan')
                                                ->label('Bantuan')
                                        ])->collapsible(),
                                ]),
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
                            Section::make()
                                ->heading('Status Pengajuan')
                                ->description('Keterangan Status Pengajuan')
                                ->schema(
                                    [
                                        Select::make('status_pengajuan')
                                            ->options(Pengajuan::class)
                                            ->disabledOn(['create', 'edit'])
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
                    ->toggleable(isToggledHiddenByDefault: true)
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
                TextColumn::make('alamat')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('alamatKK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    // ->badge()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('status_pengajuan')
                    ->searchable()
                    ->badge()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('kesehatan.kes_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions(
                [
                    Tables\Actions\ViewAction::make()->button()->color('primary')->iconSize('sm'),

                    ActionsAction::make('Batalkan')->action(
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
                                $record->audits()->latest()->first()->user
                            )
                            ->seconds(5)
                            ->persistent()
                            ->send())
                        ->visible(function (Penduduk $record) {

                            //kirim ke rt yang dengan wilayah id penduduk ini
                            $roles = auth()->user()->roles->pluck('name');
                            $pengajuan = $record->status_pengajuan->value;
                            foreach ($roles as $role) {
                                if ($role == 'RT' && ($pengajuan == 'DALAM PROSES')) {
                                    return true;
                                }
                            }
                        }),

                    ActionGroup::make([
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
                                    if ($role == 'super_admin' && ($pengajuan == 'DALAM PROSES' || $pengajuan == 'SELESAI')) {
                                        return true;
                                    }
                                }
                            }),

                        Tables\Actions\DeleteAction::make(),
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
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
                                                        TextEntry::make('alamat')
                                                            ->label('Alamat')
                                                            ->inlineLabel()
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('pendidikan')
                                                            ->label('   Pendidikan')
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
                                                        TextEntry::make('status')
                                                            ->label('Status')
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
                            ComponentsSection::make()
                                ->heading('Informasi Tambahan')
                                ->description('Berikut adalah informasi tambahan dari penduduk')
                                ->schema(
                                    [
                                        Split::make([
                                            Grid::make(2)
                                                ->schema([
                                                    ComponentsGroup::make([
                                                        TextEntry::make('kesehatan.kesehatan_jaminan')
                                                            ->label('Kesehatan')
                                                            ->weight(FontWeight::Bold)
                                                            ->badge()
                                                            ->copyable()
                                                            ->inlineLabel()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                        TextEntry::make('bantuan')
                                                            ->label('Bantuan')
                                                            ->weight(FontWeight::Bold)
                                                            ->copyable()
                                                            ->inlineLabel()
                                                            ->copyMessage('Telah Disalin!')
                                                            ->copyMessageDuration(1000),
                                                    ]),

                                                ])
                                        ]),
                                    ]
                                )->columnSpan(['lg' => 2]),
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
                                                if ($roles->contains('super_admin') && $record->status_pengajuan->value == 'DALAM PROSES') {
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
                                                    if ($role == 'RT' && ($pengajuan == 'DALAM PROSES')) {
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

    public static function getEloquentQuery(): Builder
    {
        ($roles = auth()->user()->roles->pluck('name'));

        if ($roles->contains('RT')) {
            $wilayahId = auth()->user()->wilayahRoles->pluck('wilayah.wilayah_id')->first();
            $queryRT = parent::getEloquentQuery()->whereHas('kartuKeluarga', function ($query) use ($wilayahId) {
                $query->where('wilayah_id', $wilayahId);
            });
        } elseif ($roles->contains('RW')) {
            $wilayahId = auth()->user()->wilayahRoles->pluck('wilayah.wilayah_id');
            $queryRW = parent::getEloquentQuery()->whereHas('kartuKeluarga', function ($query) use ($wilayahId) {
                $query->whereIn('wilayah_id', $wilayahId);
            });
        } else {
            $queryAdmin = parent::getEloquentQuery();
        }

        return $queryRT ?? $queryRW ?? $queryAdmin;
    }

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
                Fieldset::make('Informasi Pribadi')
                    ->schema([
                        TextInput::make('nik')
                            ->label('NIK')
                            ->numeric()
                            ->disabled()
                            ->default(fn () => (string) rand(1000000000000000, 9999999999999999))
                            ->minLength(16)
                            ->unique(Penduduk::class, 'nik')
                            ->dehydrated()
                            ->placeholder('Masukkan NIK')
                            ->required(),
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(),
                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(2)
                            ->placeholder('Masukkan alamat kartu keluarga')
                            ->required(),
                        Select::make('agama')
                            ->options(Agama::class)
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->options(JenisKelamin::class)
                            ->required(),
                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required(),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->options(GolonganDarah::class)
                            ->required(),
                        Select::make('etnis_suku')
                            ->label('Etnis Suku')
                            ->options(EtnisSuku::class),
                        Select::make('pendidikan')
                            ->label('Pendidikan')
                            ->options(Pendidikan::class),
                        Select::make('status_perkawinan')
                            ->label('Status Perkawinan')
                            ->options(Perkawinan::class),
                        Select::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->options(Pekerjaan::class)
                            // ->searchingMessage('Mencari Jenis Pekerjaan')
                            // ->searchable()
                            ->required(),
                        Select::make('kewarganegaraan')
                            ->label('Kewarganegaraan')
                            ->options(Kewarganegaraan::class)
                            ->required(),
                        Select::make('status')
                            ->options(Status::class)
                            ->label('Status Kependudukan')
                            ->required(),
                        TextInput::make('ayah')
                            ->label('Nama Ayah'),
                        TextInput::make('ibu')
                            ->label('Nama Ibu'),
                        Select::make('status_tempat_tinggal')
                            ->label('Status Tempat Tinggal')
                            ->options(StatusTempatTinggal::class),
                        Select::make('status_hubungan')
                            ->label('Status Hubungan')
                            // ->disabled(
                            //     function (Select $component) {
                            //         if ($component->getConcealingComponent()->getContainer()->getParentComponent()->getKey('kartukeluarga')) {
                            //             return true;
                            //         } else {
                            //             return false;
                            //         }
                            //     }
                            // )
                            ->options(
                                function (Select $component) {
                                    $parentComponent = $component->getConcealingComponent()->getContainer()->getParentComponent();

                                    $key = $parentComponent->getKey('kartukeluarga');

                                    $cases = ($key) ? StatusHubungan::KEPALA_KELUARGA : StatusHubungan::cases();

                                    return collect($cases)
                                        ->reject(fn ($case) => (!$key && $case->value === StatusHubungan::KEPALA_KELUARGA->value))
                                        ->mapWithKeys(fn ($case) => [
                                            ($case?->value ?? $case->name) => $case->getLabel() ?? $case->name,
                                        ])
                                        ->all();
                                }
                            )

                    ])->columns(2),
                Fieldset::make('Informasi Kontak')
                    ->schema([
                        TextInput::make('telepon')
                            ->tel()
                            ->disabled()
                            ->label('Telepon'),
                        TextInput::make('email')
                            ->email()
                            ->label('Email'),
                    ])->columns(2),
                Fieldset::make('Informasi Tambahan')
                    ->schema([
                        Select::make('Sasaran Bantuan')
                            ->label('Sasaran Bantuan')
                            ->options(
                                [
                                    'Tidak' => 'Tidak',
                                    'Ya' => 'Ya',
                                ]
                            ),
                        Select::make('bantuans')
                            // ->relationship('bantuans', 'jenis_bantuan')
                            ->native(false)
                            ->preload()
                            ->options(
                                fn () => Bantuan::all()->mapWithKeys(fn ($bantuan) => [
                                    $bantuan->bantuan_id => $bantuan->jenis_bantuan,
                                ])
                            )
                            // ->multiple()
                            ->createOptionForm(
                                [
                                    TextInput::make('jenis_bantuan')
                                        ->label('Jaminan Kesehatan'),
                                    TextInput::make('keterangan_bantuan')
                                        ->label('Keterangan Bantuan'),
                                    DateTimePicker::make('tanggal_bantuan')
                                        ->label('tanggal_bantuan')
                                ]
                            )
                            ->createOptionUsing(
                                function (array $data) {
                                    $bantuans = Bantuan::find(2)->penduduks()->get();
                                    dd($bantuans);


                                    return Bantuan::create([
                                        'jenis_bantuan' => $data['jenis_bantuan'],
                                        'keterangan_bantuan' => $data['keterangan_bantuan'],
                                        'tanggal_bantuan' => $data['tanggal_bantuan'],
                                    ]);
                                }
                            ),

                        Select::make('kesehatan')
                            ->label('kesehatan')
                            ->options(Pengajuan::class),
                    ])->columns(2),


                // Select::make('kes_id')
                //     ->label('Jaminan Kesehatan')
                //     ->preload()
                //     ->relationship('kesehatan', 'kes_id')
                //     ->multiple()
                //     ->searchingMessage('Mencari Jaminan Kesehatan')
                //     ->createOptionForm(
                //         [
                //             TextInput::make('kes_id')
                //                 ->label('Jaminan Kesehatan')
                //         ]
                //     ),

            ];
    }
}
