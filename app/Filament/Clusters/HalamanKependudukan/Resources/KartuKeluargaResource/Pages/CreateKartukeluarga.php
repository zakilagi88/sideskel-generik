<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;

use App\Enums\Kependudukan\StatusDasarType;
use App\Enums\Kependudukan\StatusPengajuanType;
use App\Facades\Deskel;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Models\{Bantuan, KartuKeluarga, Kelahiran, Pendatang, Penduduk, Wilayah};
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Filament\Actions\Action;
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

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data berhasil ditambahkan';
    }
    protected function getSteps(): array
    {

        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;

        $settings = app(GeneralSettings::class)->toArray();
        $deskel = Deskel::getFacadeRoot()->struktur;

        return [
            Step::make('KartuKeluarga')
                ->label('Informasi Kartu Keluarga')
                ->schema([
                    Group::make()
                        ->extraAttributes(['class' => 'flex justify-center bg-primary-400 p-2 rounded-lg'])
                        ->schema([
                            Placeholder::make('')
                                ->content(
                                    new HtmlString('<p class="text-lg text-center text-white">Informasi Kartu Keluarga</p>')
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
                                    fn(Select $component) => $component
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
                                    fn(Get $get): array => match ($get('cek_kk')) {
                                        'Ya' => [
                                            Select::make('kk_id')
                                                ->label('Nama Kepala Keluarga')
                                                ->required()
                                                ->options(
                                                    fn(): Collection =>
                                                    KartuKeluarga::with(['kepalaKeluarga', 'wilayah'])
                                                        ->byWilayah($authUser, $descendants)
                                                        ->get()
                                                        ->map(function ($item) {
                                                            return [
                                                                'key' => $item->kk_id,
                                                                'value' => ($item->kepalaKeluarga?->nama_lengkap ?? '(Belum ada Kepala)') . ' - ' . $item->kk_id . ' - ' . $item->wilayah?->wilayah_nama,
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
                                                ->dehydrated(fn(?string $state): bool => filled($state))
                                                ->required(fn(string $operation): bool => $operation === 'create'),
                                            Select::make('parent_id')
                                                ->label(fn() => $settings['sebutan_wilayah'][$deskel][0])
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn(Set $set) => $set('children_id', null))
                                                ->options(fn() => Wilayah::tree()->get()->where('depth', 0)->pluck('wilayah_nama', 'wilayah_id'))
                                                ->columnStart(1),
                                            Select::make('children_id')
                                                ->label(fn() => $settings['sebutan_wilayah'][$deskel][1])
                                                ->reactive()
                                                ->searchable()
                                                ->preload()
                                                ->options(fn(Get $get) => Wilayah::where('parent_id', $get('parent_id') ?? null)->pluck('wilayah_nama', 'wilayah_id'))
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
                        ->hidden(fn(Get $get): bool => $get('cek_kk') === 'Ya')
                        ->schema([
                            Placeholder::make('')
                                ->content(new HtmlString('<p class="text-lg text-center text-white">Informasi Kepala Keluarga</p>'))
                        ]),
                    Group::make()
                        ->key('kepalakeluarga')
                        ->hidden(fn(Get $get): bool => $get('cek_kk') === 'Ya')
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
                            Placeholder::make('')
                                ->content(
                                    fn(Get $get): Htmlable => $get('cek_kk') === 'Ya' ? new HtmlString(
                                        '<p class="text-lg text-center">Untuk Menambah Anggota Keluarga, Klik <span class="font-bold">Tambah Anggota Keluarga</span> pada Bagian Bawah Formulir</p>'
                                    ) :
                                        new HtmlString('<p class="text-lg text-center">Jika ada Anggota Keluarga, Klik <span class="font-bold">Tambah Anggota Keluarga</span></p>')
                                ),

                            KartukeluargaResource::getAnggotaKeluargaFormSchema()
                                ->defaultItems(fn(Get $get) => $get('cek_kk') === 'Ya' ? 1 : 0),
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

    private function createKK(array $data): Model
    {
        return KartuKeluarga::create([
            'kk_id' => $data['kk_id'],
            'wilayah_id' => $data['children_id'],
            'kk_alamat' => $data['kk_alamat'],
        ]);
    }


    private function createPenduduk(array $data, KartuKeluarga $kartuKeluarga, $authUser): Model
    {
        return $kartuKeluarga->penduduks()->create([
            'nik' => $data['nik'],
            'nama_lengkap' => strtoupper($data['nama_lengkap']),
            'is_nik_sementara' => $data['is_nik_sementara'] ?? false,
            'jenis_identitas' => $data['jenis_identitas'] ?? 'KTP',
            'alamat_sekarang' => strtoupper($data['alamat_sekarang']),
            'alamat_sebelumnya' => strtoupper($data['alamat_sebelumnya']) ?? null,
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
            'status_pengajuan' => $authUser->hasRole('Admin') ? StatusPengajuanType::DIVERIFIKASI : StatusPengajuanType::BELUM_DIVERIFIKASI,
            'status_tempat_tinggal' => $data['status_tempat_tinggal'] ?? null,
            'status_hubungan' => $data['status_hubungan'] ?? null,
            'email' => $data['email'] ?? null,
            'telepon' => $data['telepon'] ?? null
        ]);
    }

    private function createDinamika($penduduk, $dinamikaType, $dinamikaId, $jenisDinamika, $data)
    {
        $penduduk->dinamikas()->create([
            'dinamika_type' => $dinamikaType,
            'dinamika_id' => $dinamikaId,
            'jenis_dinamika' => $jenisDinamika,
            'catatan_dinamika' => $data['catatan_dinamika'],
            'tanggal_dinamika' => $data['tanggal_dinamika'],
            'tanggal_lapor' => $data['tanggal_lapor'],
        ]);
    }

    private function createKesehatanAnak($penduduk, $data)
    {
        $penduduk->kesehatanAnak()->create([
            'ibu_id' => $data['nik_ibu'] ?? null,
            'berat_badan' => $data['berat_lahir'] ?? null,
            'tinggi_badan' => $data['tinggi_lahir'] ?? null,
        ]);
    }

    private function createKelahiran(array $data, Penduduk $penduduk): Model
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

    private function createPendatang(array $data, Penduduk $penduduk): Model
    {
        return $penduduk->pendatang()->create([
            'alamat_sebelumnya' => $data['alamat_sebelumnya'] ?? null,
        ]);
    }

    protected function createData(array $data): void
    {
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();

        // Cek Data Kepala Keluarga
        $kartuKeluarga = $data['cek_kk'] === 'Ya' ? KartuKeluarga::where('kk_id', $data['kk_id'])->with('kepalaKeluarga')->first() : $this->createKK($data);
        $kk_kepala = $data['cek_kk'] === 'Ya' ? $kartuKeluarga->kepalaKeluarga : $this->createPenduduk($data, $kartuKeluarga, $authUser);

        // Cek Terdaftar Bantuan
        if (isset($data['bantuans'])) {
            $bantuan = Bantuan::find($data['bantuans']);
            $target = $data['bantuan_sasaran'] === 'Penduduk' ? $kk_kepala : $kartuKeluarga;
            $target->bantuans()->attach($bantuan);
        }

        // Memasukkan ke Pendatang jika Kepala Keluarga belum Ada
        if ($data['cek_kk'] !== 'Ya') {
            $pendatang = $this->createPendatang($data, $kk_kepala);
            $this->createDinamika($kk_kepala, Pendatang::class, $pendatang->id, 'Pindah Masuk', $data);
        }

        // Cek Data Anggota Keluarga
        if (isset($data['anggotaKeluarga'])) {
            foreach ($data['anggotaKeluarga'] as $anggota) {
                $penduduk = $this->createPenduduk($anggota, $kartuKeluarga, $authUser);

                if (isset($anggota['bantuans'])) {
                    $bantuan = Bantuan::find($anggota['bantuans']);
                    $penduduk->bantuans()->attach($bantuan);
                }

                $umur = Carbon::parse($anggota['tanggal_lahir'])->diffInYears(Carbon::now());

                if ($umur < 5) {
                    $kelahiran = $this->createKelahiran($anggota, $penduduk);
                    $this->createDinamika($penduduk, Kelahiran::class, $kelahiran->id, 'Lahir', $anggota);
                    $this->createKesehatanAnak($penduduk, $anggota);
                } else {
                    $pendatang = $this->createPendatang($anggota, $penduduk);
                    $this->createDinamika($penduduk, Pendatang::class, $pendatang->id, 'Pindah Masuk', $anggota);
                }
            }
        }
    }
}
