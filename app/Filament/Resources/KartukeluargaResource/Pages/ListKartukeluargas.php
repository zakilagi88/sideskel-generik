<?php

namespace App\Filament\Resources\KartukeluargaResource\Pages;

use App\Enum\Penduduk\Agama;
use App\Enum\Penduduk\JenisKelamin;
use App\Enum\Penduduk\Pekerjaan;
use App\Enum\Penduduk\Pendidikan;
use App\Enum\Penduduk\Pernikahan;
use App\Enum\Penduduk\Status;
use App\Filament\Resources\KartukeluargaResource;
use App\Imports\Import;
use App\Imports\ImportPenduduk;
use App\Imports\KartuKeluargaImport;
use App\Jobs\ImportJob;
use App\Models\AnggotaKeluarga;
use App\Models\Kab_Kota;
use App\Models\KartuKeluarga;
use App\Models\kecamatan;
use App\Models\Kelurahan;
use App\Models\Penduduk;
use App\Models\Provinsi;
use App\Models\SLS;
use App\Models\User;
use Closure;
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Unique;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use NunoMaduro\Collision\Adapters\Phpunit\State;

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
            Actions\CreateAction::make('Tambah Kartu Keluarga')
                ->label('Tambah Kartu Keluarga')
                ->closeModalByClickingAway()
                ->modalAlignment('center')
                ->modalCancelActionLabel('Batal')
                // ->modelLabel('Kartu Keluarga')
                ->modalHeading('Tambah Kartu Keluarga')
                ->modalWidth('8xl')
                ->steps([
                    Step::make('Informasi Kartu Keluarga')
                        ->description('Masukkan informasi kartu keluarga')
                        ->schema([
                            Fieldset::make('Informasi Kartu Keluarga')
                                ->schema([
                                    Group::make()
                                        ->schema([
                                            TextInput::make('kk_id')
                                                ->label('No KK')
                                                ->unique(KartuKeluarga::class, 'kk_id')
                                                ->dehydrated()
                                                ->placeholder('Masukkan nomor kartu keluarga')
                                                ->required(),
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
                                                    function (Action $action) {
                                                        return $action
                                                            ->label('Tambah Penduduk')
                                                            ->modalWidth('7xl')
                                                            ->modalHeading('Tambah Penduduk');
                                                    }
                                                )
                                                ->options(
                                                    Penduduk::whereDoesntHave('anggotaKeluarga')->pluck('nama_lengkap', 'nik')
                                                )
                                                ->required(),
                                            Textarea::make('kk_alamat')
                                                ->label('Alamat')
                                                ->rows(5)
                                                ->placeholder('Masukkan alamat kartu keluarga')
                                                ->required(),
                                            Toggle::make('setuju')
                                                ->label('Data yang saya masukkan sudah benar')
                                                ->inline()
                                                ->onColor('success')
                                                ->offColor('danger')
                                                ->live()
                                                ->rules([
                                                    fn (Get $get): Closure =>
                                                    function (string $attribute, $value, Closure $fail) use ($get) {
                                                        if ($value === false) {
                                                            $fail("Anda harus menyetujui pernyataan ini");
                                                        }
                                                    },
                                                ])
                                        ])->columnSpan(['lg' => 1]),
                                    Group::make()
                                        ->schema([
                                            Select::make('prov_id')
                                                ->label('Provinsi')
                                                ->options(
                                                    Provinsi::pluck('prov_nama', 'prov_id')
                                                )
                                                ->live()
                                                ->dehydrated(),
                                            // ->required(),
                                            Select::make('kabkota_id')
                                                ->label('Kab/Kota')
                                                ->options(
                                                    fn (Get $get): Collection => Kab_Kota::query()
                                                        ->where('prov_id', $get('prov_id'))
                                                        ->pluck('kabkota_nama', 'kabkota_id')
                                                )
                                                ->dehydrated()
                                                ->live()
                                                ->preload(),
                                            // ->required(),

                                            Select::make('kec_id')
                                                ->label('Kecamatan')
                                                ->options(
                                                    fn (Get $get): Collection => kecamatan::query()
                                                        ->where('kabkota_id', $get('kabkota_id'))
                                                        ->pluck('kec_nama', 'kec_id')
                                                )
                                                ->dehydrated()
                                                ->live()
                                                ->preload(),
                                            // ->required(),
                                            Select::make('kel_id')
                                                ->label('Desa/Kelurahan')
                                                ->options(
                                                    fn (Get $get): Collection => Kelurahan::query()
                                                        ->where('kec_id', $get('kec_id'))
                                                        ->pluck('kel_nama', 'kel_id')

                                                )
                                                ->live()
                                                ->dehydrated(),
                                            // ->required(),
                                            Select::make('sls_id')
                                                ->label('RW/RT')
                                                ->options(
                                                    fn (Get $get): Collection => SLS::query()
                                                        ->where('kel_id', $get('kel_id'))
                                                        ->pluck('sls_nama', 'sls_id')
                                                )
                                                ->live()

                                                ->dehydrated(),

                                        ])->columnSpan(['lg' => 1]),

                                ])->columns(2)->columnSpanFull(),
                            Group::make()
                                ->schema([
                                    Fieldset::make('Anggota Keluarga')
                                        ->label('Anggota Keluarga')
                                        ->schema([
                                            Repeater::make('anggotaKeluarga')
                                                ->hiddenLabel()
                                                ->reactive()
                                                ->addable()
                                                ->cloneable()
                                                ->model(AnggotaKeluarga::class)
                                                ->schema([
                                                    Select::make('nik')
                                                        ->label('NIK')
                                                        ->live(onBlur: true)
                                                        ->relationship('penduduk', 'nama_lengkap')
                                                        ->searchable()
                                                        ->preload()
                                                        ->createOptionForm(
                                                            [
                                                                KartukeluargaResource::getFormSchema()
                                                            ]
                                                        )
                                                        ->createOptionAction(
                                                            function (Action $action) {
                                                                return $action
                                                                    ->label('Tambah Penduduk')
                                                                    ->modalWidth('7xl')
                                                                    ->modalHeading('Tambah Penduduk');
                                                            }
                                                        )
                                                        ->options(
                                                            Penduduk::whereDoesntHave('anggotaKeluarga')->pluck('nama_lengkap', 'nik')
                                                        )

                                                        ->required(),

                                                    Select::make('hubungan')
                                                        ->label('Hubungan')
                                                        ->live(onBlur: true)
                                                        ->searchable()
                                                        ->required()
                                                        ->options(
                                                            [
                                                                'KEPALA KELUARGA' => 'KEPALA KELUARGA',
                                                                'ISTRI' => 'ISTRI',
                                                                'ANAK' => 'ANAK',
                                                                'FAMILI LAIN' => 'FAMILI LAIN',
                                                            ]

                                                        )

                                                ])->columnSpanFull()->columns(2)->itemLabel(
                                                    function (array $state): ?string {
                                                        $nik = $state['nik'] ?? null;
                                                        $hubungan = $state['hubungan'] ?? null;
                                                        $penduduk = Penduduk::where('nik', $nik)->first();
                                                        $nama = $penduduk->nama_lengkap ?? null;
                                                        if ($nik && $hubungan) {
                                                            return "$nama ( $hubungan )";
                                                        } else {
                                                            return "Belum ada Anggota Keluarga";
                                                        }
                                                    }

                                                )->deleteAction(
                                                    fn (Action $action) => $action->requiresConfirmation(),
                                                )->addActionLabel('Tambah')
                                                ->collapsible()
                                                ->collapseAllAction(
                                                    fn (Action $action) => $action->label('Tutup Semua'),
                                                )->expandAllAction(
                                                    fn (Action $action) => $action->label('Buka Semua'),
                                                )->markAsRequired()
                                        ]),

                                ])->columnSpanFull(),


                        ])->columns(2),

                ])
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Kartu Keluarga Telah Dibuat')
                        ->body('Silahkan cek data kartu keluarga di tabel'),
                )
                ->using(function (array $data) {

                    $kartuKeluarga = KartuKeluarga::create([
                        'kk_id' => $data['kk_id'],
                        'kk_alamat' => strtoupper($data['kk_alamat']),
                        'kel_id' => $data['kel_id'],
                        'sls_id' => $data['sls_id'],
                    ]);

                    $kepalaKeluarga = $kartuKeluarga->kepalaKK()->create([
                        'nik' => $data['kk_kepala'],
                        'hubungan' => 'KEPALA KELUARGA',
                    ]);


                    foreach ($data['anggotaKeluarga'] as $anggota) {
                        $kartuKeluarga->anggotaKeluarga()->create([
                            'nik' => $anggota['nik'],
                            'hubungan' => $anggota['hubungan'],
                        ]);
                    }

                    // Mengasosiasikan anggota keluarga dengan Kartu Keluarga yang sesuai
                    $penduduk = Penduduk::where('nik', $data['kk_kepala'])->first();
                    // Mengatur nilai kk_kepala di Kartu Keluarga dengan associated penduduk
                    $kartuKeluarga->kepalaKeluarga()->associate($penduduk);

                    $kartuKeluarga->save();

                    $admin = User::whereHas('roles', function ($query) {
                        $query->where('name', 'super_admin');
                    })->get();

                    Notification::make()
                        ->success()
                        ->title('Kartu Keluarga Telah Dibuat')
                        ->body('Silahkan cek data kartu keluarga di tabel')
                        ->sendToDatabase($admin);
                }),
            ActionsAction::make('Import')
                ->form([
                    FileUpload::make('file')
                        ->label('Upload File')
                        ->required()
                        ->disk('local')
                        ->directory('import')
                        ->rules([
                            'required',
                            'mimes:xlsx,xls',
                        ])
                        ->openable()
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ])
                ->label('Import File')

                ->requiresConfirmation()
                ->action(
                    function (array $data) {
                        // Notification::make()
                        //     ->title('Import Data Kartu Keluarga')
                        //     ->body(
                        //         'Data kartu keluarga sedang diimport, silahkan tunggu hingga selesai'
                        //     )
                        //     ->send()
                        //     ->duration(5000);
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


        ];
    }

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make('Tambah Kartu Keluarga');
        $uploadFile = ActionsAction::make('Import');
        return view('filament.custom.upload-file', compact('data', 'uploadFile'))->with([
            'isImporting' => $this->isImporting,
            'importFinished' => $this->importFinished,
            'importProgress' => $this->importProgress,

        ]);
    }


    public function getTabs(): array
    {
        $data = [];

        $sls_data = SLS::orderBy('rw_id')->orderBy('rt_id')->get();

        $current_rw_id = 0;

        foreach ($sls_data as $sls) {
            $rw_id = $sls->rw_id;

            if (!isset($data[$rw_id])) {
                $data[$rw_id] = Tab::make('RW ', $sls->rw_groups->rw_nama)
                    ->modifyQueryUsing(function (Builder $query) use ($rw_id) {
                        $query->whereHas('sls', function ($query) use ($rw_id) {
                            $query->where('rw_id', $rw_id);
                        });
                    })->label($sls->rw_groups->rw_nama)->badge(KartuKeluarga::whereHas('sls', function ($query) use ($rw_id) {
                        $query->where('rw_id', $rw_id);
                    })->count())->badgeColor('success');
            }
        }

        return
            [
                'all' => Tab::make('Semua', function (Builder $query) {
                    $query->where('sls_id', '!=', null);
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

    // public function save()
    // {

    //     $this->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     if ($this->fileSelected) {
    //         if ($this->isImporting) {

    //             $this->cancelImport();
    //         }

    //         $this->isImporting = true;

    //         $this->importFilePath = $this->file->store('import');

    //         $batch = Bus::batch([
    //             new ImportJob($this->importFilePath),
    //         ])->dispatch();

    //         $this->batchId = $batch->id;

    //         session()->flash('message', 'Import data kartu keluarga berhasil dilakukan');
    //     } else {
    //         $this->addError('file', 'File tidak boleh kosong');
    //     }
    // }

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
