<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;

use App\Enums\Kependudukan\StatusDasarType;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Models\{Bantuan, KartuKeluarga, Kelahiran, Pendatang, Penduduk, Wilayah};
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\{Group, Placeholder, Section, Select, Textarea, TextInput, ToggleButtons, Wizard, Wizard\Step};
use Filament\Forms\{Form, Get, Set};
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use function Filament\Support\is_app_url;

class CreateKartukeluarga extends CreateRecord
{

    use HasWizard;

    protected static string $resource = KartukeluargaResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Tambah Data Keluarga';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function hasSkippableSteps(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false)
                    ->persistStepInQueryString('step'),
            ])

            ->columns(null);
    }

    // protected function handleRecordCreation(array $data): Model
    // {

    //     return static::getModel()::create([
    //         'kk_id' => $data['kk_id'],
    //         'wilayah_id' => $data['children_id'],
    //         'kk_alamat' => $data['kk_alamat'],
    //         'kk_kepala' => null,
    //     ]);
    // }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data berhasil ditambahkan';
    }
    protected function getSteps(): array
    {

        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;

        // $wilayah = Wilayah::tree()->find($authUser->wilayah_id);

        return [
            Step::make('KartuKeluarga')
                ->label('Informasi Kartu Keluarga')
                ->schema([
                    Group::make()
                        ->extraAttributes(['class' => 'flex justify-center bg-primary-400 p-2 rounded-lg'])
                        ->schema([
                            Placeholder::make('')
                                ->content(
                                    new HtmlString(
                                        '<p class="text-lg text-center text-white">Informasi Kartu Keluarga</p>'
                                    )
                                )
                        ]),
                    Section::make()
                        ->key('cek_kk')
                        ->schema([
                            Select::make('cek_kk')
                                ->label('Apakah sudah tersedia data Kepala Keluarga?')
                                ->live()
                                ->options([
                                    'Ya' => 'Ya',
                                    'Tidak' => 'Tidak',
                                ])
                                ->default('Ya')
                                ->afterStateUpdated(
                                    fn (Select $component) => $component
                                        ->getContainer()
                                        ->getComponent('kartukeluargaCek')
                                        ->getChildComponentContainer()
                                        ->fill()
                                )
                                ->required()
                                ->columnSpanFull(),
                            Section::make()
                                ->key('kartukeluargaCek')
                                ->schema(
                                    fn (Get $get): array => match ($get('cek_kk')) {
                                        'Ya' => [
                                            Select::make('kk_id')
                                                ->label('Nama Kepala Keluarga')
                                                ->required()
                                                ->options(
                                                    fn (Get $get): Collection =>
                                                    KartuKeluarga::with(['kepalaKeluarga', 'wilayah'])
                                                        ->byWilayah($authUser, $descendants)
                                                        ->get()
                                                        ->map(function ($item) {
                                                            return [
                                                                'key' => $item->kk_id,
                                                                'value' => $item->kepalaKeluarga?->nama_lengkap . ' - ' . $item->kk_id . ' - ' . $item->wilayah?->wilayah_nama,
                                                            ];
                                                        })->pluck('value', 'key')

                                                )
                                                ->placeholder('Pilih Nama Kepala Keluarga')
                                                ->columnSpanFull(),
                                        ],
                                        'Tidak' => [

                                            TextInput::make('kk_id')
                                                ->label('Nomor Kartu Keluarga')
                                                ->minLength(16)
                                                ->unique(ignoreRecord: true)
                                                ->numeric()
                                                ->placeholder('Masukkan nomor kartu keluarga')
                                                ->dehydrated(
                                                    fn (?string $state): bool => filled($state)
                                                )
                                                ->required(fn (string $operation): bool => $operation === 'create'),
                                            Select::make('parent_id')
                                                ->label('RW')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(
                                                    fn (Set $set, Get $get) => $set('children_id', null)
                                                )
                                                ->options(
                                                    function () {
                                                        return Wilayah::tree()->get()->where('depth', 0)->pluck('wilayah_nama', 'wilayah_id');
                                                    }
                                                )
                                                ->columnStart(1),
                                            Select::make('children_id')
                                                ->label('RT')
                                                ->reactive()
                                                ->searchable()
                                                ->preload()
                                                ->options(
                                                    function (Get $get) {
                                                        return wilayah::where('parent_id', $get('parent_id') ?? null)->pluck('wilayah_nama', 'wilayah_id');
                                                    }
                                                )
                                                ->required(),

                                            Textarea::make('kk_alamat')
                                                ->label('Alamat Kartu Keluarga')
                                                ->rows(3)
                                                ->placeholder('Masukkan alamat kartu keluarga')
                                                ->required()
                                        ],
                                        default => [],
                                    }
                                )
                                ->columnSpanFull(),
                        ]),
                    Group::make()
                        ->extraAttributes(['class' => 'flex justify-center bg-primary-400 p-2 rounded-lg'])
                        ->hidden(
                            fn (Get $get): bool => $get('cek_kk') === 'Ya'
                        )
                        ->schema([
                            Placeholder::make('')

                                ->content(
                                    new HtmlString(
                                        '<p class="text-lg text-center text-white">Informasi Kepala Keluarga</p>'
                                    )
                                )
                        ]),
                    Group::make()
                        ->key('kepalakeluarga')
                        ->hidden(
                            fn (Get $get): bool => $get('cek_kk') === 'Ya'
                        )
                        ->schema(PendudukResource::getPendudukFormSchema())->columnSpanFull(),

                ]),
            Step::make('AnggotaKeluarga')
                ->label('Anggota Keluarga')
                ->schema([
                    Group::make()
                        ->extraAttributes(['class' => 'flex justify-center bg-primary-400 p-2 rounded-lg'])
                        ->schema([
                            Placeholder::make('')
                                ->content(
                                    new HtmlString(
                                        '<p class="text-lg text-center text-white">Informasi Anggota Keluarga</p>'
                                    )
                                )
                        ]),
                    Group::make()
                        ->key('anggotakeluarga')
                        ->schema([
                            ToggleButtons::make('cek_anggota')
                                ->label('Apakah ada anggota keluarga lain?')
                                ->inline()
                                ->live()
                                ->options([
                                    'Ya' => 'Ya',
                                    'Tidak' => 'Tidak',
                                ])
                                ->default('Ya')
                                ->key('cek_anggota')
                                ->columnSpanFull(),
                            KartukeluargaResource::getAnggotaKeluargaFormSchema(),
                        ]),
                ]),
        ];
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $data = $this->form->getState();
            $this->createData($data);
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }

    public function createKK(array $data): Model
    {
        return KartuKeluarga::create([
            'kk_id' => $data['kk_id'],
            'wilayah_id' => $data['children_id'],
            'kk_alamat' => $data['kk_alamat'],
        ]);
    }

    public function addKepalaKK(array $data, KartuKeluarga $kartuKeluarga): Model
    {
        return $kartuKeluarga->penduduks()->create(
            [
                'nik' => $data['nik'],
                'nama_lengkap' => $data['nama_lengkap'],
                'is_nik_sementara' => $data['is_nik_sementara'] ?? false,
                'jenis_identitas' => $data['jenis_identitas'] ?? 'KTP',
                'alamat_sekarang' => $data['alamat_sekarang'],
                'alamat_sebelumnya' => $data['alamat_sebelumnya'] ?? null,
                'agama' => $data['agama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'golongan_darah' => $data['golongan_darah'],
                'etnis_suku' => $data['etnis_suku'] ?? null,
                'pendidikan' => $data['pendidikan'],
                'status_perkawinan' => $data['status_perkawinan'],
                'pekerjaan' => $data['pekerjaan'],
                'kewarganegaraan' => $data['kewarganegaraan'],
                'nama_ayah' => $data['nama_ayah'] ?? null,
                'nama_ibu' => $data['nama_ibu'] ?? null,
                'nik_ayah' => $data['nik_ayah'] ?? null,
                'nik_ibu' => $data['nik_ibu'] ?? null,
                'status_dasar' => StatusDasarType::HIDUP,
                'status_penduduk' => $data['status_penduduk'] ?? 'TETAP',
                'status_tempat_tinggal' => $data['status_tempat_tinggal'] ?? null,
                'status_hubungan' => $data['status_hubungan'] ?? null,
                'email' => $data['email'] ?? null,
                'telepon' => $data['telepon'] ?? null
            ]
        );
    }

    public function createAnggotaKK(array $data, KartuKeluarga $kartuKeluarga): Model
    {
        return $kartuKeluarga->penduduks()->create([
            'nik' => $data['nik'],
            'nama_lengkap' => $data['nama_lengkap'],
            'is_nik_sementara' => $data['is_nik_sementara'] ?? false,
            'jenis_identitas' => $data['jenis_identitas'] ?? 'KTP',
            'alamat_sekarang' => $data['alamat_sekarang'],
            'alamat_sebelumnya' => $data['alamat_sebelumnya'] ?? null,
            'agama' => $data['agama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'golongan_darah' => $data['golongan_darah'],
            'etnis_suku' => $data['etnis_suku'] ?? null,
            'pendidikan' => $data['pendidikan'],
            'status_perkawinan' => $data['status_perkawinan'],
            'pekerjaan' => $data['pekerjaan'],
            'kewarganegaraan' => $data['kewarganegaraan'],
            'nama_ayah' => $data['nama_ayah'] ?? null,
            'nama_ibu' => $data['nama_ibu'] ?? null,
            'nik_ayah' => $data['nik_ayah'] ?? null,
            'nik_ibu' => $data['nik_ibu'] ?? null,
            'status_dasar' => StatusDasarType::HIDUP,
            'status_penduduk' => $data['status_penduduk'] ?? 'Tetap',
            'status_tempat_tinggal' => $data['status_tempat_tinggal'] ?? null,
            'status_hubungan' => $data['status_hubungan'] ?? null,
            'email' => $data['email'] ?? null,
            'telepon' => $data['telepon'] ?? null
        ]);
    }

    public function createKelahiran(array $data, Penduduk $penduduk): Model
    {
        return $penduduk->kelahiran()->create([
            'anak_ke' => $data['anak_ke'],
            'tempat_lahir' => $data['tempat_lahir'],
            'jenis_lahir' => $data['jenis_lahir'],
            'penolong_lahir' => $data['penolong_lahir'],
            'berat_lahir' => $data['berat_lahir'],
            'tinggi_lahir' => $data['tinggi_lahir'],
        ]);
    }

    public function createPendatang(array $data, Penduduk $penduduk): Model
    {
        return $penduduk->pendatang()->create([
            'alamat_sebelumnya' => $data['alamat_sebelumnya'] ?? null,
        ]);
    }


    protected function createData(array $data): void
    {

        if ($data['cek_kk'] === 'Ya') {
            $kartuKeluarga = KartuKeluarga::where('kk_id', $data['kk_id'])->first();
            $kk_kepala = $kartuKeluarga->with('kepalaKeluarga')->first();

            if (isset($data['bantuans'])) {
                $bantuan = Bantuan::find($data['bantuans']);
                if ($data['bantuan_sasaran'] === 'Penduduk')
                    $kk_kepala->bantuans()->attach($bantuan);
                else
                    $kartuKeluarga->bantuans()->attach($bantuan);
            }
        } else {
            $kartuKeluarga = $this->createKK($data);
            $kk_kepala = $this->addKepalaKK($data, $kartuKeluarga);
            $pendatang = $this->createPendatang($data, $kk_kepala);
            $kk_kepala->dinamikas()->create([
                'dinamika_type' => Pendatang::class,
                'dinamika_id' => $pendatang->id,
                'jenis_dinamika' => 'Pindah Masuk',
                'catatan_dinamika' => $data['catatan_dinamika'],
                'tanggal_dinamika' => $data['tanggal_dinamika'],
                'tanggal_lapor' => $data['tanggal_lapor'],
            ]);

            if (isset($data['bantuans'])) {
                $bantuan = Bantuan::find($data['bantuans']);
                $kk_kepala->bantuans()->attach($bantuan);
            }
        }

        if (isset($data['anggotaKeluarga'])) {
            foreach ($data['anggotaKeluarga'] as $anggota) {
                $penduduk = $this->createAnggotaKK($anggota, $kartuKeluarga);

                if (isset($anggota['bantuans'])) {
                    $bantuan = Bantuan::find($anggota['bantuans']);
                    $penduduk->bantuans()->attach($bantuan);
                }
                $umur = Carbon::parse($anggota['tanggal_lahir'])->diffInYears(Carbon::now());

                if ($umur < 5) {
                    $kelahiran  = $this->createKelahiran($anggota, $penduduk);

                    $penduduk->dinamikas()->create([
                        'dinamika_type' => Kelahiran::class,
                        'dinamika_id' => $kelahiran->id,
                        'jenis_dinamika' => 'Lahir',
                        'catatan_dinamika' => $anggota['catatan_dinamika'],
                        'tanggal_dinamika' => $anggota['tanggal_dinamika'],
                        'tanggal_lapor' => $anggota['tanggal_lapor'],
                    ]);

                    $penduduk->kesehatanAnak()->create([
                        'ibu_id' => $anggota['nik_ibu'] ?? null,
                        'berat_badan' => $anggota['berat_lahir'] ?? null,
                        'tinggi_badan' => $anggota['tinggi_lahir'] ?? null,
                    ]);
                } else {
                    $pendatang = $this->createPendatang($anggota, $penduduk);

                    $penduduk->dinamikas()->create([
                        'dinamika_type' => Pendatang::class,
                        'dinamika_id' => $pendatang->id,
                        'jenis_dinamika' => 'Pindah Masuk',
                        'catatan_dinamika' => $anggota['catatan_dinamika'],
                        'tanggal_dinamika' => $anggota['tanggal_dinamika'],
                        'tanggal_lapor' => $anggota['tanggal_lapor'],
                    ]);
                }
            }
        }
    }
}
