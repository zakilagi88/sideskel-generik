<?php

namespace App\Filament\Resources\KartukeluargaResource\Pages;

use App\Enum\Penduduk\StatusHubungan;
use Closure;

use App\Filament\Resources\KartukeluargaResource;
use App\Filament\Resources\PendudukResource;
use App\Imports\KartuKeluargaImport;
use App\Jobs\ImportJob;
use App\Models\{Kelurahan, KartuKeluarga, Penduduk, Provinsi, User, Wilayah, AnggotaKeluarga, KabKota, Kecamatan};
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\{Fieldset, FileUpload, Group, Repeater, Select, TextArea, Toggle, TextInput};

use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;

use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ListKartukeluargas extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = KartukeluargaResource::class;

    public $file;
    public $batchId;
    public $isImporting = false;
    public $importFilePath;
    public $importProgress = 0;
    public $importFinished = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Kartu Keluarga'),
            //     ->form([
            //         KartukeluargaResource::getFormSchema()
            //     ]),
            // Actions\CreateAction::make('Tambah Kartu Keluarga')
            //     ->label('Tambah Kartu Keluarga')
            //     ->closeModalByClickingAway()
            //     ->modalAlignment('center')
            //     ->modalCancelActionLabel('Batal')
            //     ->modalHeading('Tambah Kartu Keluarga')
            //     ->modalWidth('7xl')
            //     ->steps([
            //         Step::make('Informasi Kartu Keluarga')
            //             ->description('Masukkan informasi kartu keluarga')
            //             ->schema([
            //                 Fieldset::make('Informasi Kartu Keluarga')
            //                     ->schema([
            //                         Group::make()
            //                             ->schema([
            //                                 TextInput::make('kk_id')
            //                                     ->label('No KK')
            //                                     ->unique(KartuKeluarga::class, 'kk_id')
            //                                     ->dehydrated()
            //                                     ->placeholder('Masukkan nomor kartu keluarga')
            //                                     ->required(),
            //                                 Select::make('kk_kepala')
            //                                     ->label('KK Kepala Keluarga')
            //                                     ->relationship('kepalaKeluarga', 'nama_lengkap')
            //                                     ->searchable()
            //                                     ->preload()
            //                                     ->createOptionForm(
            //                                         [
            //                                             PendudukResource::getFormSchema()
            //                                         ]
            //                                     )
            //                                     ->createOptionAction(
            //                                         function (Action $action) {
            //                                             return $action
            //                                                 ->label('Tambah Penduduk')
            //                                                 ->modalWidth('7xl')
            //                                                 ->modalHeading('Tambah Penduduk');
            //                                         }
            //                                     )
            //                                     ->options(
            //                                         Penduduk::whereDoesntHave('kartuKeluarga')->pluck('nama_lengkap', 'nik')
            //                                     )
            //                                     ->required(),
            //                                 Textarea::make('kk_alamat')
            //                                     ->label('Alamat')
            //                                     ->rows(5)
            //                                     ->placeholder('Masukkan alamat kartu keluarga')
            //                                     ->required(),
            //                                 Toggle::make('setuju')
            //                                     ->label('Data yang saya masukkan sudah benar')
            //                                     ->inline()
            //                                     ->onColor('success')
            //                                     ->offColor('danger')
            //                                     ->live()
            //                                     ->rules([
            //                                         fn (Get $get): Closure =>
            //                                         function (string $attribute, $value, Closure $fail) use ($get) {
            //                                             if ($value === false) {
            //                                                 $fail("Anda harus menyetujui pernyataan ini");
            //                                             }
            //                                         },
            //                                     ])
            //                             ])->columnSpan(['lg' => 1]),
            //                         Group::make()
            //                             ->schema([
            //                                 Select::make('prov_id')
            //                                     ->label('Provinsi')
            //                                     ->options(
            //                                         Provinsi::pluck('prov_nama', 'prov_id')
            //                                     )
            //                                     ->live()
            //                                     ->dehydrated(),
            //                                 // ->required(),
            //                                 Select::make('kabkota_id')
            //                                     ->label('Kab/Kota')
            //                                     ->options(
            //                                         fn (Get $get): Collection => KabKota::query()
            //                                             ->where('prov_id', $get('prov_id'))
            //                                             ->pluck('kabkota_nama', 'kabkota_id')
            //                                     )
            //                                     ->dehydrated()
            //                                     ->live()
            //                                     ->preload(),
            //                                 // ->required(),

            //                                 Select::make('kec_id')
            //                                     ->label('Kecamatan')
            //                                     ->options(
            //                                         fn (Get $get): Collection => Kecamatan::query()
            //                                             ->where('kabkota_id', $get('kabkota_id'))
            //                                             ->pluck('kec_nama', 'kec_id')
            //                                     )
            //                                     ->dehydrated()
            //                                     ->live()
            //                                     ->preload(),
            //                                 // ->required(),
            //                                 Select::make('kel_id')
            //                                     ->label('Desa/Kelurahan')
            //                                     ->options(
            //                                         fn (Get $get): Collection => Kelurahan::query()
            //                                             ->where('kec_id', $get('kec_id'))
            //                                             ->pluck('kel_nama', 'kel_id')

            //                                     )
            //                                     ->live()
            //                                     ->dehydrated(),
            //                                 // ->required(),
            //                                 Select::make('wilayah_id')
            //                                     ->label('RW/RT')
            //                                     ->options(
            //                                         fn (Get $get): Collection => wilayah::query()
            //                                             ->where('kel_id', $get('kel_id'))
            //                                             ->pluck('wilayah_nama', 'wilayah_id')
            //                                     )

            //                                     ->dehydrated(),

            //                             ])->columnSpan(['lg' => 1]),

            //                     ])->columns(2)->columnSpanFull(),
            //                 Group::make()
            //                     ->schema([
            //                         Fieldset::make('Anggota Keluarga')
            //                             ->label('Anggota Keluarga')
            //                             ->schema([
            //                                 Repeater::make('anggotaKeluarga')
            //                                     ->hiddenLabel()
            //                                     ->reactive()
            //                                     ->addable()
            //                                     ->cloneable()
            //                                     ->model(AnggotaKeluarga::class)
            //                                     ->schema([
            //                                         Select::make('nik')
            //                                             ->label('NIK')
            //                                             ->live(onBlur: true)
            //                                             ->relationship('penduduk', 'nama_lengkap')
            //                                             ->searchable()
            //                                             ->preload()
            //                                             ->createOptionForm(
            //                                                 [
            //                                                     PendudukResource::getFormSchema()
            //                                                 ]
            //                                             )
            //                                             ->createOptionAction(
            //                                                 function (Action $action) {
            //                                                     return $action
            //                                                         ->label('Tambah Penduduk')
            //                                                         ->modalWidth('7xl')
            //                                                         ->modalHeading('Tambah Penduduk');
            //                                                 }
            //                                             )
            //                                             ->options(
            //                                                 Penduduk::whereDoesntHave('kartuKeluarga')->pluck('nama_lengkap', 'nik')
            //                                             )

            //                                             ->required(),

            //                                         Select::make('status_hubungan')
            //                                             ->label('Status Hubungan')
            //                                             ->live(onBlur: true)
            //                                             ->searchable()
            //                                             ->required()
            //                                             ->options(
            //                                                 StatusHubungan::class
            //                                             )

            //                                     ])->columnSpanFull()->columns(2)->itemLabel(
            //                                         function (array $state): ?string {
            //                                             $nik = $state['nik'] ?? null;
            //                                             $hubungan = $state['hubungan'] ?? null;
            //                                             $penduduk = Penduduk::where('nik', $nik)->first();
            //                                             $nama = $penduduk->nama_lengkap ?? null;
            //                                             if ($nik && $hubungan) {
            //                                                 return "$nama ( $hubungan )";
            //                                             } else {
            //                                                 return "Belum ada Anggota Keluarga";
            //                                             }
            //                                         }

            //                                     )->deleteAction(
            //                                         fn (Action $action) => $action->requiresConfirmation(),
            //                                     )->addActionLabel('Tambah')
            //                                     ->collapsible()
            //                                     ->collapseAllAction(
            //                                         fn (Action $action) => $action->label('Tutup Semua'),
            //                                     )->expandAllAction(
            //                                         fn (Action $action) => $action->label('Buka Semua'),
            //                                     )->markAsRequired()
            //                             ]),

            //                     ])->columnSpanFull(),


            //             ])->columns(2),

            //     ])
            //     ->successNotification(
            //         Notification::make()
            //             ->success()
            //             ->title('Kartu Keluarga Telah Dibuat')
            //             ->body('Silahkan cek data kartu keluarga di tabel'),
            //     )
            //     ->using(function (array $data) {

            //         DB::beginTransaction();
            //         try {
            //             // $kartuKeluarga = self::tambahKartukeluarga($data);
            //             // self::tambahKepalaKeluarga($data, $kartuKeluarga);
            //             // self::tambahAnggotaKeluarga($data, $kartuKeluarga);
            //             // self::assosiasiKepalaKeluarga($data, $kartuKeluarga);
            //             $kartuKeluarga = KartuKeluarga::create([
            //                 'kk_id' => $data['kk_id'],
            //                 'kk_alamat' => strtoupper($data['kk_alamat']),
            //                 'kel_id' => $data['kel_id'],
            //                 'wilayah_id' => $data['wilayah_id'],
            //             ]);

            //             // dd($data, $kartuKeluarga);

            //             $kepalaKeluarga = Penduduk::findOrFail($data['kk_kepala']);
            //             $kartuKeluarga->kepalaKeluarga()->associate($kepalaKeluarga);


            //             $kartuKeluarga->anggotaKeluarga()->create([
            //                 'nik' => $kepalaKeluarga->nik,
            //                 'hubungan' => 'KEPALA KELUARGA',
            //             ]);


            //             foreach ($data['anggotaKeluarga'] as $anggota) {
            //                 $anggotaKeluarga = Penduduk::findOrFail($anggota['nik']);

            //                 $hubungan = $anggota['hubungan'];

            //                 $kartuKeluarga->anggotaKeluarga()->create([
            //                     'nik' => $anggotaKeluarga->nik,
            //                     'hubungan' => $hubungan,
            //                 ]);
            //             }

            //             $kartuKeluarga->save();
            //             // $penduduk = Penduduk::where('nik', $data['kk_kepala'])->first();
            //             // $kartuKeluarga->kepalaKeluarga()->associate($penduduk);

            //             DB::commit();
            //         } catch (\Throwable $th) {
            //             DB::rollback();
            //             throw $th;
            //         }

            //         $admin = User::whereHas('roles', function ($query) {
            //             $query->where('name', 'super_admin');
            //         })->get();

            //         Notification::make()
            //             ->success()
            //             ->title('Kartu Keluarga Telah Dibuat')
            //             ->body('Silahkan cek data kartu keluarga di tabel')
            //             ->sendToDatabase($admin);
            //     }),
            ActionsAction::make('Import')
                ->form([
                    FileUpload::make('file')
                        ->label('Upload File')
                        ->required()
                        ->disk('local')
                        ->directory('import')
                        ->rules([
                            'required',
                            'mimes:xlsx,xls,csv',
                        ])
                        ->openable()
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ])
                ->label('Import File')

                ->action(
                    function (array $data) {
                        self::import($data['file']);
                        Notification::make()
                            ->title('Import Data Kartu Keluarga')
                            ->body(
                                'Data kartu keluarga berhasil diimport'
                            )->sendToDatabase(User::whereHas('roles', function ($query) {
                                $query->where('name', 'super_admin');
                            })->get())

                            ->send();
                    }

                )
                ->successRedirectUrl(route('filament.admin.resources.kartukeluarga.index')),
        ];
    }

    protected function tambahKartukeluarga(array $data)
    {
        $kartuKeluarga = KartuKeluarga::create([
            'kk_id' => $data['kk_id'],
            'kk_alamat' => strtoupper($data['kk_alamat']),
            'kel_id' => $data['kel_id'],
            'wilayah_id' => $data['wilayah_id'],
        ]);

        return $kartuKeluarga;
    }

    protected function tambahKepalaKeluarga(array $data, KartuKeluarga $kartuKeluarga)
    {
        $kepalaKeluarga = $kartuKeluarga->kepalaKeluarga()->create([
            'nik' => $data['kk_kepala'],
            'hubungan' => 'KEPALA KELUARGA',
        ]);

        return $kepalaKeluarga;
    }

    protected function tambahAnggotaKeluarga(array $data, KartuKeluarga $kartuKeluarga)
    {
        foreach ($data['anggotaKeluarga'] as $anggota) {
            $kartuKeluarga->anggotaKeluarga()->create([
                'nik' => $anggota['nik'],
                'hubungan' => $anggota['hubungan'],
            ]);
        }

        return $kartuKeluarga;
    }

    protected function assosiasiKepalaKeluarga(array $data, KartuKeluarga $kartuKeluarga)
    {
        $penduduk = Penduduk::where('nik', $data['kk_kepala'])->first();
        $kartuKeluarga->kepalaKeluarga()->associate($penduduk);
        $kartuKeluarga->save();

        return $kartuKeluarga;
    }


    // public function getHeader(): ?View
    // {
    //     $createAction = Actions\CreateAction::make('Tambah Kartu Keluarga')->label('Tambah Kartu Keluarga');
    //     $importAction = ActionsAction::make('Import');

    //     return view('filament.custom.upload-file', compact('createAction', 'importAction'))->with([
    //         'isImporting' => $this->isImporting,
    //         'importFinished' => $this->importFinished,
    //         'importProgress' => $this->importProgress,
    //     ]);
    // }



    public function getTabs(): array
    {
        $data = [];

        $wilayah_data = wilayah::orderBy('rw_id')->orderBy('rt_id')->get();

        $current_rw_id = 0;

        foreach ($wilayah_data as $wilayah) {
            $rw_id = $wilayah->rw_id;

            if (!isset($data[$rw_id])) {
                $data[$rw_id] = Tab::make('RW ', $wilayah->rws->rw_nama)
                    ->modifyQueryUsing(function (Builder $query) use ($rw_id) {
                        $query->whereHas('wilayah', function ($query) use ($rw_id) {
                            $query->where('rw_id', $rw_id);
                        });
                    })->label($wilayah->rws->rw_nama)->badge(KartuKeluarga::whereHas('wilayah', function ($query) use ($rw_id) {
                        $query->where('rw_id', $rw_id);
                    })->count())->badgeColor('success');
            }
        }

        return
            [
                'all' => Tab::make('Semua', function (Builder $query) {
                    $query->where('wilayah_id', '!=', null);
                })->label('Semua')->badge(KartuKeluarga::count())->badgeColor('primary'),
            ]
            + $data;
    }

    public function import($data)
    {
        $this->importFilePath = $data;
        $this->isImporting = true;
        $batch = Bus::batch([
            new ImportJob($this->importFilePath),
        ])->dispatch();

        $this->batchId = $batch->id;
    }

    public function cancelImport()
    {
        if ($this->batchId) {
            Bus::findBatch($this->batchId)->cancel();
        }
    }

    public function getImportBatchProperty()
    {
        if (!$this->batchId) {
            return null;
        }
        return Bus::findBatch($this->batchId);
    }


    public function updateImportProgress()
    {
        if (!$this->importBatch) {
            return;
        } else {
            $this->importProgress = $this->importBatch->progress();
            $this->importFinished = $this->importBatch->finished();
            if ($this->importFinished) {
                Storage::delete($this->importFilePath);
                $this->isImporting = false;
                $this->importFilePath = null;
                $this->batchId = null;
            }
        }
    }
}