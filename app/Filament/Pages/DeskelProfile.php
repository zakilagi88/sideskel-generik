<?php

namespace App\Filament\Pages;

use App\Enums\Desa\TipologiType;
use App\Models\{DesaKelurahan, DesaKelurahanProfile, KabKota, Kecamatan, Penduduk, Provinsi};
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\{FileUpload, Grid as ComponentsGrid, Group as ComponentsGroup, Repeater, RichEditor, Select, Split as SplitForm, Tabs as ComponentsTabs, Tabs\Tab as TabsTab, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\{Grid, Group, ImageEntry, Section, Split, Tabs, TextEntry, Tabs\Tab};
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DeskelProfile extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithHeaderActions;

    public DesaKelurahanProfile $deskel;

    protected static ?string $navigationIcon = 'fas-house-chimney';

    protected static ?string $navigationLabel = 'Profil Desa/Kelurahan';

    protected static string $view = 'filament.pages.deskel-profil';

    protected static ?string $slug = 'deskel-profile';

    protected static ?string $title = '';

    public ?array $data = [];
    public int $pdd = 0;

    use HasPageShield;

    public function mount(DesaKelurahanProfile $deskel): void
    {
        $this->deskel = $deskel->first() ?? new DesaKelurahanProfile();
        $this->pdd = Penduduk::count();
    }

    public function sumWilayah(array $data): string
    {
        $sum = 0;

        foreach ($data as $item) {
            foreach ($item as $value) {
                // Periksa apakah nilai tidak null dan merupakan string numerik
                if ($value !== null && is_numeric($value) && trim($value) !== '') {
                    $sum += floatval($value);
                }
            }
        }
        return number_format($sum, 2, ',', '.');
    }

    public static function generateArrayTextInput($data): array
    {
        $textInputs = [];
        foreach ($data as $key => $value) {
            $textInputs[] = TextInput::make(name: $key)
                ->numeric()
                ->label($value)
                ->placeholder('Masukkan ' . $value)
                ->suffix('Buah')
                ->dehydrateStateUsing(fn (string $state): string => (int) ($state))
                ->validationAttribute($value)
                ->minValue(0);
        }

        return $textInputs;
    }

    public function deskelData(): array
    {
        return [
            'id' => $this->deskel->id ?? null,
            'prov_nama' => $this->deskel->dk?->kec?->kabkota?->prov?->prov_nama ?? null,
            'prov_id' => $this->deskel->dk?->kec?->kabkota?->prov_id ?? null,
            'kabkota_nama' => $this->deskel->dk?->kec?->kabkota?->kabkota_nama ?? null,
            'kabkota_id' => $this->deskel->dk?->kec?->kabkota_id ?? null,
            'kec_nama' => $this->deskel->dk?->kec?->kec_nama ?? null,
            'kec_id' => $this->deskel->dk?->kec_id ?? null,
            'sebutan' => $this->deskel->sebutan ?? null,
            'deskel_nama' => $this->deskel->deskel_nama ?? null,
            'gambar' => $this->deskel->gambar ?? null,
            'deskel_id' => $this->deskel->deskel_id ?? null,
            'deskel_nama' => $this->deskel->dk->deskel_nama ?? null,
            'kepala' => $this->deskel->dk->kepala ?? null,
            'struktur' => $this->deskel->struktur ?? null,
            'alamat' => $this->deskel->alamat ?? null,
            'kodepos' => $this->deskel->kodepos ?? null,
            'koordinat_lat' => $this->deskel->koordinat_lat ?? null,
            'koordinat_long' => $this->deskel->koordinat_long ?? null,
            'luas_total' => $this->sumWilayah($this->deskel->luaswilayah) ?? null,
            'luaswilayah' =>
            [
                0 => [
                    'lahan_sawah' =>  $this->deskel->luaswilayah[0]['lahan_sawah'] ?? 0,
                    'lahan_ladang' => $this->deskel->luaswilayah[0]['lahan_ladang'] ?? 0,
                    'lahan_perkebunan' => $this->deskel->luaswilayah[0]['lahan_perkebunan'] ?? 0,
                    'lahan_peternakan' => $this->deskel->luaswilayah[0]['lahan_peternakan'] ?? 0,
                    'lahan_hutan' => $this->deskel->luaswilayah[0]['lahan_hutan'] ?? 0,
                    'waduk_danau_situ' => $this->deskel->luaswilayah[0]['waduk_danau_situ'] ?? 0,
                    'lainnya' => $this->deskel->luaswilayah[0]['lainnya'] ?? 0
                ]
            ],
            'thn_bentuk' => $this->deskel->thn_bentuk ?? null,
            'dasar_hukum_bentuk' => $this->deskel->dasar_hukum_bentuk ?? null,
            'tipologi' => $this->deskel->tipologi ?? null,
            'klasifikasi' => $this->deskel->klasifikasi ?? null,
            'kategori' => $this->deskel->kategori ?? null,
            'orbitrasi' =>
            [
                0 => [
                    'pusat_kec' => $this->deskel->orbitrasi[0]['pusat_kec'] ?? 0,
                    'pusat_kota' => $this->deskel->orbitrasi[0]['pusat_kota'] ?? 0,
                    'pusat_kab' => $this->deskel->orbitrasi[0]['pusat_kab'] ?? 0,
                    'pusat_prov' => $this->deskel->orbitrasi[0]['pusat_prov'] ?? 0,
                ]
            ],
            'jmlh_sert_tanah' => $this->deskel->jmlh_sert_tanah ?? null,
            'jmlh_pdd' => $this->pdd ?? null,
            'tanah_kas' => $this->deskel->tanah_kas ?? null,
            'bts_utara' => $this->deskel->bts_utara ?? null,
            'bts_timur' => $this->deskel->bts_timur ?? null,
            'bts_selatan' => $this->deskel->bts_selatan ?? null,
            'bts_barat' => $this->deskel->bts_barat ?? null,
            'kantor' => $this->deskel->kantor ?? null,
            'prasarana_kesehatan' =>
            [
                0 => [
                    'puskesmas' => $this->deskel->prasarana_kesehatan[0]['puskesmas'] ?? 0,
                    'puskesmas_pembantu' => $this->deskel->prasarana_kesehatan[0]['puskesmas_pembantu'] ?? 0,
                    'poskesdes' => $this->deskel->prasarana_kesehatan[0]['poskesdes'] ?? 0,
                    'posyandu_polindes' => $this->deskel->prasarana_kesehatan[0]['posyandu_polindes'] ?? 0,
                    'rumah_sakit' => $this->deskel->prasarana_kesehatan[0]['rumah_sakit'] ?? 0,
                ]
            ],
            'prasarana_pendidikan' =>
            [
                0 => [
                    'perpustakaan' => $this->deskel->prasarana_pendidikan[0]['perpustakaan'] ?? 0,
                    'gedung_sekolah_paud' => $this->deskel->prasarana_pendidikan[0]['gedung_sekolah_paud'] ?? 0,
                    'gedung_sekolah_tk' => $this->deskel->prasarana_pendidikan[0]['gedung_sekolah_tk'] ?? 0,
                    'gedung_sekolah_sd' => $this->deskel->prasarana_pendidikan[0]['gedung_sekolah_sd'] ?? 0,
                    'gedung_sekolah_smp' => $this->deskel->prasarana_pendidikan[0]['gedung_sekolah_smp'] ?? 0,
                    'gedung_sekolah_sma' => $this->deskel->prasarana_pendidikan[0]['gedung_sekolah_sma'] ?? 0,
                    'gedung_perguruan_tinggi' => $this->deskel->prasarana_pendidikan[0]['gedung_perguruan_tinggi'] ?? 0,
                ]
            ],
            'prasarana_ibadah' =>
            [
                0 => [
                    'masjid' => $this->deskel->prasarana_ibadah[0]['masjid'] ?? 0,
                    'mushola' => $this->deskel->prasarana_ibadah[0]['mushola'] ?? 0,
                    'gereja' => $this->deskel->prasarana_ibadah[0]['gereja'] ?? 0,
                    'pura' => $this->deskel->prasarana_ibadah[0]['pura'] ?? 0,
                    'vihara' => $this->deskel->prasarana_ibadah[0]['vihara'] ?? 0,
                    'klenteng' => $this->deskel->prasarana_ibadah[0]['klenteng'] ?? 0,
                ]
            ],
            'prasarana_umum' =>
            [
                0 => [
                    'olahraga' => $this->deskel->prasarana_umum[0]['olahraga'] ?? 0,
                    'kesenian_budaya' => $this->deskel->prasarana_umum[0]['kesenian_budaya'] ?? 0,
                    'balai_pertemuan' => $this->deskel->prasarana_umum[0]['balai_pertemuan'] ?? 0,
                    'sumur' => $this->deskel->prasarana_umum[0]['sumur'] ?? 0,
                    'pasar' => $this->deskel->prasarana_umum[0]['pasar'] ?? 0,
                    'lainnya' => $this->deskel->prasarana_umum[0]['lainnya'] ?? 0,
                ]
            ],
            'prasarana_transportasi' =>
            [
                0 => [
                    'jalan_desa_kelurahan' => $this->deskel->prasarana_transportasi[0]['jalan_desa_kelurahan'] ?? 0,
                    'jalan_kabupaten' => $this->deskel->prasarana_transportasi[0]['jalan_kabupaten'] ?? 0,
                    'jalan_provinsi' => $this->deskel->prasarana_transportasi[0]['jalan_provinsi'] ?? 0,
                    'jalan_nasional' => $this->deskel->prasarana_transportasi[0]['jalan_nasional'] ?? 0,
                    'tambatan_perahu' => $this->deskel->prasarana_transportasi[0]['tambatan_perahu'] ?? 0,
                    'perahu_motor' => $this->deskel->prasarana_transportasi[0]['perahu_motor'] ?? 0,
                    'lapangan_terbang' => $this->deskel->prasarana_transportasi[0]['lapangan_terbang'] ?? 0,
                    'jembatan_besi' => $this->deskel->prasarana_transportasi[0]['jembatan_besi'] ?? 0,
                ]
            ],
            'prasarana_air_bersih' =>
            [
                0 => [
                    'hidran_air' => $this->deskel->prasarana_air_bersih[0]['hidran_air'] ?? 0,
                    'penampung_air_hujan' => $this->deskel->prasarana_air_bersih[0]['penampung_air_hujan'] ?? 0,
                    'pamsimas' => $this->deskel->prasarana_air_bersih[0]['pamsimas'] ?? 0,
                    'pengolahan_air_bersih' => $this->deskel->prasarana_air_bersih[0]['pengolahan_air_bersih'] ?? 0,
                    'sumur_gali' => $this->deskel->prasarana_air_bersih[0]['sumur_gali'] ?? 0,
                    'sumur_pompa' => $this->deskel->prasarana_air_bersih[0]['sumur_pompa'] ?? 0,
                    'tangki_air_bersih' => $this->deskel->prasarana_air_bersih[0]['tangki_air_bersih'] ?? 0,
                ]
            ],
            'prasarana_sanitasi_irigasi' =>
            [
                0 => [
                    'mck_umum' => $this->deskel->prasarana_sanitasi_irigasi[0]['mck_umum'] ?? 0,
                    'jamban_keluarga' => $this->deskel->prasarana_sanitasi_irigasi[0]['jamban_keluarga'] ?? 0,
                    'saluran_drainase' => $this->deskel->prasarana_sanitasi_irigasi[0]['saluran_drainase'] ?? 0,
                    'pintu_air' => $this->deskel->prasarana_sanitasi_irigasi[0]['pintu_air'] ?? 0,
                    'saluran_irigasi' => $this->deskel->prasarana_sanitasi_irigasi[0]['saluran_irigasi'] ?? 0,
                ]
            ],

            'visi' => $this->deskel->visi ?? null,
            'misi' => $this->deskel->misi ?? null,
            'sejarah' => $this->deskel->sejarah ?? null,
            'logo' => $this->deskel->logo ?? asset('images/logo-deskel.png'),
            'telepon' => $this->deskel->telepon ?? null,
            'email' => $this->deskel->email ?? null,
        ];
    }

    public function additionalData(): array
    {
        return [
            'prasarana_kesehatan' =>
            [
                'puskesmas' => 'Puskesmas',
                'puskesmas_pembantu' => 'Puskesmas Pembantu',
                'poskesdes' => 'Poskesdes',
                'posyandu_polindes' => 'Posyandu Dan Polindes',
                'rumah_sakit' => 'Rumah Sakit',
            ],
            'prasarana_pendidikan' =>
            [
                'perpustakaan' => 'Perpustakaan',
                'gedung_sekolah_paud' => 'Gedung Sekolah PAUD',
                'gedung_sekolah_tk' => 'Gedung Sekolah TK',
                'gedung_sekolah_sd' => 'Gedung Sekolah SD',
                'gedung_sekolah_smp' => 'Gedung Sekolah SMP',
                'gedung_sekolah_sma' => 'Gedung Sekolah SMA',
                'gedung_perguruan_tinggi' => 'GedunG Perguruan Tinggi',
            ],
            'prasarana_ibadah' =>
            [
                'masjid' => 'Masjid',
                'mushola' => 'Mushola',
                'gereja' => 'Gereja',
                'pura' => 'Pura',
                'vihara' => 'Vihara',
                'klenteng' => 'Klenteng',
            ],
            'prasarana_umum' =>
            [
                'olahraga' => 'Olahraga',
                'kesenian_budaya' => 'Kesenian/Budaya',
                'balai_pertemuan' => 'Balai Pertemuan',
                'sumur' => 'Sumur',
                'pasar' => 'Pasar',
                'lainnya' => 'Lainnya',
            ],
            'prasarana_transportasi' =>
            [
                'jalan_desa_kelurahan' => 'Jalan Desa/Kelurahan',
                'jalan_kabupaten' => 'Jalan Kabupaten',
                'jalan_provinsi' => 'Jalan Provinsi',
                'jalan_nasional' => 'Jalan Nasional',
                'tambatan_perahu' => 'Tambatan Perahu',
                'perahu_motor' => 'Perahu Motor',
                'lapangan_terbang' => 'Lapangan Terbang',
                'jembatan_besi' => 'Jembatan Besi',
            ],
            'prasarana_air_bersih' =>
            [
                'hidran_air' => 'Hidran Air',
                'penampung_air_hujan' => 'Penampung Air Hujan',
                'pamsimas' => 'Pamsimas',
                'pengolahan_air_bersih' => 'Pengolahan Air Bersih',
                'sumur_gali' => 'Sumur Gali',
                'sumur_pompa' => 'Sumur Pompa',
                'tangki_air_bersih' => 'Tangki Air Bersih',
            ],
            'prasarana_sanitasi_irigasi' =>
            [
                'mck_umum' => 'MCK Umum',
                'jamban_keluarga' => 'Jamban Keluarga',
                'saluran_drainase' => 'Saluran Drainase',
                'pintu_air' => 'Pintu Air',
                'saluran_irigasi' => 'Saluran Irigasi',
            ]

        ];
    }

    public function repeaterAdditionalData($key, $label, $sebutan): Repeater
    {
        return Repeater::make(name: $key)
            ->label(fn () => 'Prasarana ' . $label . ' ' . $sebutan)
            ->defaultItems(1)
            ->addable(false)
            ->reorderable(false)
            ->deletable(false)
            ->schema(
                [
                    ComponentsGrid::make([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                    ])->schema(
                        $this->generateArrayTextInput(
                            $this->additionalData()[$key]
                        )
                    )
                ]
            );
    }
    protected function getHeaderActions(): array
    {
        return [
            'edit' =>
            ActionsAction::make('edit')
                ->icon('heroicon-o-pencil')
                ->modalWidth(MaxWidth::SixExtraLarge)
                ->fillForm(self::deskelData())
                ->form(fn (Form $form) => DeskelProfile::deskelForm($form))
                ->action(
                    function (array $data): void {
                        $this->submit();
                    }
                ),
        ];
    }

    public function deskelForm(Form $form): Form
    {
        $sebutan = $this->deskel->sebutan ?? 'Desa/Kelurahan';
        return $form
            ->schema([
                SplitForm::make([
                    FileUpload::make('gambar')
                        ->label('Gambar Kantor Desa/Kelurahan')
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend('gambar-kantor-'),
                        )
                        ->disk('public')
                        ->directory('deskel')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->imagePreviewHeight('250')
                        ->loadingIndicatorPosition('right')
                        ->panelAspectRatio('2:1')
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadButtonPosition('left')
                        ->uploadProgressIndicatorPosition('left'),
                    FileUpload::make('logo')
                        ->label('Gambar Logo Desa/Kelurahan')
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend('gambar-logo-'),
                        )
                        ->disk('public')
                        ->directory('deskel')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->imagePreviewHeight('250')
                        ->loadingIndicatorPosition('right')
                        ->panelAspectRatio('2:1')
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadButtonPosition('left')
                        ->uploadProgressIndicatorPosition('left'),
                ]),
                ComponentsTabs::make('tabs')
                    ->tabs([
                        TabsTab::make('Data Pokok Desa/Kelurahan')
                            ->schema([
                                TextInput::make('sebutan')
                                    ->label('Sebutan Desa/Kelurahan')
                                    ->placeholder('Masukkan Sebutan untuk Desa/Kelurahan')
                                    ->hint('Desa / Kelurahan / Nagari / Lainnya')
                                    ->live(onBlur: true),
                                Select::make('prov_id')
                                    ->label('Provinsi')
                                    ->placeholder('Pilih Provinsi')
                                    ->searchable()
                                    ->native(false)
                                    ->required()
                                    ->options(
                                        Provinsi::pluck('prov_nama', 'prov_id')
                                    )
                                    ->live()
                                    ->dehydrated(),
                                Select::make('kabkota_id')
                                    ->label('Kab/Kota')
                                    ->placeholder('Pilih Kab/Kota')
                                    ->searchable()
                                    ->native(false)
                                    ->required()
                                    ->options(
                                        fn (Get $get): Collection => KabKota::query()
                                            ->where('prov_id', $get('prov_id'))
                                            ->pluck('kabkota_nama', 'kabkota_id')
                                    )
                                    ->dehydrated()
                                    ->live()
                                    ->searchable()
                                    ->preload(),
                                Select::make('kec_id')
                                    ->label('Kecamatan')
                                    ->placeholder('Pilih Kecamatan')
                                    ->searchable()
                                    ->native(false)
                                    ->required()
                                    ->options(
                                        fn (Get $get): Collection => Kecamatan::query()
                                            ->where('kabkota_id', $get('kabkota_id'))
                                            ->pluck('kec_nama', 'kec_id')
                                    )
                                    ->dehydrated()
                                    ->live()
                                    ->preload(),
                                Select::make('deskel_id')
                                    ->label('Desa/Kelurahan')
                                    ->searchable()
                                    ->placeholder('Pilih Desa/Kelurahan')
                                    ->unique(DesaKelurahanProfile::class, 'deskel_id', ignoreRecord: true)
                                    ->native(false)
                                    ->required()
                                    ->options(
                                        fn (Get $get): Collection => DesaKelurahan::query()
                                            ->where('kec_id', $get('kec_id'))
                                            ->pluck('deskel_nama', 'deskel_id')
                                    )
                                    ->live()
                                    ->dehydrated(),
                                TextInput::make('kodepos')
                                    ->label(fn () => 'Kode Pos ' . $sebutan)
                                    ->placeholder(fn () => 'Kode Pos ' . $sebutan)
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('thn_bentuk')
                                    ->label(fn () => 'Tahun Pembentukan ' . $sebutan)
                                    ->placeholder(fn () => 'Tahun Pembentukan ' . $sebutan)
                                    ->numeric()
                                    ->minValue(0)
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('dasar_hukum_bentuk')
                                    ->label(fn () => 'Dasar Hukum ' . $sebutan)
                                    ->placeholder(fn () => 'Dasar Hukum ' . $sebutan)
                                    ->nullable()
                                    ->autofocus()
                                    ->required(),
                                // Select::make('kepala')
                                //     ->label(fn () => 'Kepala ' . $sebutan)
                                //     ->placeholder(fn () => 'Kepala ' . $sebutan),
                                TextInput::make('koordinat_lat')
                                    ->label(fn () => 'Longitude ' . $sebutan)
                                    ->placeholder(fn () => 'Luas Wilayah ' . $sebutan)
                                    ->nullable(),
                                TextInput::make('koordinat_lat')
                                    ->label(fn () => 'Lattitude ' . $sebutan)
                                    ->placeholder(fn () => 'Luas Wilayah ' . $sebutan)
                                    ->nullable(),
                                Textarea::make('alamat')
                                    ->label(fn () => 'Alamat Kantor ' . $sebutan)
                                    ->autofocus()
                                    ->required()
                                    ->placeholder(fn () => 'Alamat Kantor ' . $sebutan),
                                TextInput::make('bts_utara')
                                    ->label(fn () => 'Batas Utara ' . $sebutan)->nullable()
                                    ->placeholder(fn () => 'Batas Utara ' . $sebutan),
                                TextInput::make('bts_timur')
                                    ->label(
                                        fn () => 'Batas Timur ' . $sebutan
                                    )->nullable()
                                    ->placeholder(fn () => 'Batas Timur ' . $sebutan),
                                TextInput::make('bts_selatan')
                                    ->label(fn () => 'Batas Selatan ' . $sebutan)->nullable()
                                    ->placeholder(fn () => 'Batas Selatan ' . $sebutan),
                                TextInput::make('bts_barat')
                                    ->label(fn () => 'Batas Barat ' . $sebutan)->nullable()
                                    ->placeholder(fn () => 'Batas Barat ' . $sebutan),

                            ]),
                        TabsTab::make('Data Umum Desa/Kelurahan ')
                            ->schema([
                                Select::make('tipologi')
                                    ->label(fn () => 'Tipologi ' . $sebutan)
                                    ->placeholder(fn () => 'Tipologi ' . $sebutan)
                                    ->options(TipologiType::class),
                                ComponentsGroup::make()
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('luas_total')
                                            ->label(fn () => 'Luas Wilayah ' . $sebutan)
                                            ->placeholder('Luas Wilayah')
                                            ->nullable()
                                            ->columnSpan(1)
                                            ->suffix('Ha'),
                                        Repeater::make('luaswilayah')
                                            ->hiddenLabel()
                                            ->defaultItems(1)
                                            ->addable(false)
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->columnSpan(2)
                                            ->schema([
                                                ComponentsGrid::make([
                                                    'default' => 2,
                                                    'sm' => 2,
                                                    'md' => 2,
                                                ])
                                                    ->schema([
                                                        TextInput::make('lahan_sawah')
                                                            ->label('Lahan Sawah')
                                                            ->placeholder('Luas Lahan Sawah')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->suffix('Ha'),
                                                        TextInput::make('lahan_ladang')
                                                            ->label('Lahan Ladang')
                                                            ->placeholder('Luas Lahan Ladang')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->suffix('Ha'),
                                                        TextInput::make('lahan_perkebunan')
                                                            ->label('Lahan Perkebunan')
                                                            ->placeholder('Luas Lahan Perkebunan')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->suffix('Ha'),
                                                        TextInput::make('lahan_peternakan')
                                                            ->label('Lahan Peternakan')
                                                            ->placeholder('Luas Lahan Peternakan')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->suffix('Ha'),
                                                        TextInput::make('lahan_hutan')
                                                            ->label('Lahan Hutan')
                                                            ->placeholder('Luas Lahan Hutan')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->suffix('Ha'),
                                                        TextInput::make('waduk_danau_situ')
                                                            ->label('Luas Waduk/Danau/Situ')
                                                            ->placeholder('Luas Waduk/Danau/Situ')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->suffix('Ha'),
                                                        TextInput::make('lainnya')
                                                            ->label('Lahan Lainnya')
                                                            ->placeholder('Luas Lahan Lainnya')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->inputMode('decimal')
                                                            ->columnSpanFull()
                                                            ->suffix('Ha'),
                                                    ]),
                                            ]),
                                    ]),
                                TextInput::make('jmlh_sert_tanah')
                                    ->label(fn () => 'Jumlah Tanah Bersertifikat ' . $sebutan)
                                    ->placeholder(fn () => 'Jumlah Tanah Bersertifikat ' . $sebutan)
                                    ->nullable()
                                    ->numeric()
                                    ->inputMode('decimal')
                                    ->suffix('Sertifikat'),
                                TextInput::make('tanah_kas')
                                    ->label(fn () => 'Tanah Kas ' . $sebutan)
                                    ->placeholder(fn () => 'Tanah Kas Desa/Kelurahan ' . $sebutan)
                                    ->nullable()
                                    ->numeric()
                                    ->inputMode('decimal')
                                    ->suffix('Ha'),
                                Repeater::make('orbitrasi')
                                    ->label(fn () => 'Orbitrasi ' . $sebutan . ' (Jarak dari Pusat Pemerintahan)')
                                    ->defaultItems(1)
                                    ->addable(false)
                                    ->reorderable(false)
                                    ->deletable(false)
                                    ->schema([
                                        ComponentsGrid::make([
                                            'default' => 2,
                                            'sm' => 2,
                                            'md' => 2,
                                        ])
                                            ->schema([
                                                TextInput::make('pusat_kec')
                                                    ->label('Jarak dari Pusat Pemerintahan Kecamatan')
                                                    ->placeholder('Jarak')
                                                    ->nullable()
                                                    ->numeric()
                                                    ->inputMode('decimal')
                                                    ->suffix('Km'),
                                                TextInput::make('pusat_kota')
                                                    ->label('Jarak dari Pusat Pemerintahan Kab/Kota')
                                                    ->placeholder('Jarak')
                                                    ->nullable()
                                                    ->numeric()
                                                    ->inputMode('decimal')
                                                    ->suffix('Km'),
                                                TextInput::make('pusat_kab')
                                                    ->label('Jarak dari Kota/Ibukota Kabupaten')
                                                    ->placeholder('Jarak')
                                                    ->nullable()
                                                    ->numeric()
                                                    ->inputMode('decimal')
                                                    ->suffix('Km'),
                                                TextInput::make('pusat_prov')
                                                    ->label('Jarak dari Pusat Pemerintahan Provinsi')
                                                    ->placeholder('Jarak')
                                                    ->nullable()
                                                    ->numeric()
                                                    ->inputMode('decimal')
                                                    ->suffix('Km'),
                                            ])
                                    ]),
                                TextInput::make('kantor')
                                    ->label(fn () => 'Kantor ' . $sebutan)
                                    ->placeholder(fn () => 'Kantor ' . $sebutan)
                                    ->nullable(),
                                $this->repeaterAdditionalData(key: 'prasarana_kesehatan', label: 'Kesehatan', sebutan: $sebutan),
                                $this->repeaterAdditionalData(key: 'prasarana_pendidikan', label: 'Pendidikan', sebutan: $sebutan),
                                $this->repeaterAdditionalData(key: 'prasarana_ibadah', label: 'Ibadah', sebutan: $sebutan),
                                $this->repeaterAdditionalData(key: 'prasarana_umum', label: 'Umum', sebutan: $sebutan),
                                $this->repeaterAdditionalData(key: 'prasarana_transportasi', label: 'Transportasi', sebutan: $sebutan),
                                $this->repeaterAdditionalData(key: 'prasarana_air_bersih', label: 'Air Bersih', sebutan: $sebutan),
                                $this->repeaterAdditionalData(key: 'prasarana_sanitasi_irigasi', label: 'Sanitasi Irigasi', sebutan: $sebutan),


                            ]),
                        TabsTab::make('Sejarah Desa/Kelurahan')
                            ->schema([
                                Textarea::make('visi')
                                    ->label(fn () => 'Visi ' . $sebutan)->nullable()
                                    ->placeholder(fn () => 'Visi ' . $sebutan),
                                RichEditor::make('misi')
                                    ->label(fn () => 'Misi ' . $sebutan)->nullable()
                                    ->placeholder(fn () => 'Misi ' . $sebutan),
                                RichEditor::make('sejarah')
                                    ->label(fn () => 'Sejarah ' . $sebutan)->nullable()
                                    ->placeholder(fn () => 'Sejarah ' . $sebutan),
                            ]),
                        TabsTab::make('Kontak Desa/Kelurahan')
                            ->schema([
                                TextInput::make('telepon')
                                    ->label(fn () => 'Telepon ' . $sebutan)->nullable()->tel()->nullable()
                                    ->placeholder(fn () => 'Telepon ' . $sebutan),
                                TextInput::make('email')
                                    ->label(fn () => 'Email ' . $sebutan)->nullable()->email()
                                    ->placeholder(fn () => 'Email ' . $sebutan),
                            ]),
                    ]),
            ])
            ->statePath('data')
            ->model($this->deskel);
    }

    public function deskelInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state(self::deskelData())
            ->schema([
                Grid::make([
                    'sm' => 3,
                    'lg' => 3,
                ])
                    ->schema([
                        Section::make('Gambar  ')
                            ->heading('')
                            ->default('')
                            ->extraAttributes([
                                'class' => ' h-80 w-full',
                                'style' => 'background-image: url(' . ($this->deskel->gambar ? asset('storage/' . $this->deskel->gambar) : asset('images/bg-kantor.png')) . '); background-size: cover; background-position: center; background-repeat: no-repeat; border-radius: 0px; border-top-right-radius: 0.5rem; border-top-left-radius: 0.5rem;',


                            ]),
                        Section::make('Info Desa/Kelurahan')
                            ->heading('')
                            ->extraAttributes([
                                'class' => '-mt-6',
                                'style' => 'border-radius: 0px; border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;',
                            ])
                            ->columnSpanFull()
                            ->schema([
                                Group::make()
                                    ->columns([
                                        'sm' => 1,
                                        'lg' => 1,
                                        'md' => 1,
                                        'xl' => 1,
                                        '2xl' => 1,
                                    ])
                                    ->extraAttributes([
                                        'class' => 'bg-primary-400 dark:bg-info-950 rounded-lg p-2',
                                    ])
                                    ->schema([
                                        Split::make([
                                            ImageEntry::make('logo')
                                                ->hiddenLabel()
                                                ->circular()
                                                ->columnSpan(1)
                                                ->size(150)
                                                ->extraAttributes([
                                                    'class' => 'justify-center',
                                                ])
                                                ->extraImgAttributes([
                                                    'alt' => 'Logo',
                                                    'loading' => 'lazy',
                                                    'class' => ' border-4 border-gray bg-white dark:bg-info-950 p-2',
                                                    'style' => 'object-fit: contain;',
                                                ]),
                                            Group::make()
                                                ->columnStart(2)
                                                ->columnSpan(2)
                                                ->extraAttributes([
                                                    'class' => 'object-cover py-6 my-2 ',
                                                ])
                                                ->schema([
                                                    TextEntry::make('deskel_nama')
                                                        ->size('2xl')
                                                        ->placeholder(
                                                            fn (): string => 'Nama ' . ucwords(strtolower($this->deskel->sebutan))
                                                        )
                                                        ->hiddenLabel()
                                                        ->prefix(
                                                            fn (): string => ucwords(strtolower(($this->deskel->sebutan)) . ' ')
                                                        )
                                                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                                                        ->alignStart()
                                                        ->extraAttributes([
                                                            'class' => 'deskel-profile-section',
                                                            'style' => 'font-weight: 700; font-size: 1.5rem;',
                                                        ])
                                                        ->columnSpanFull(),
                                                    TextEntry::make('prov_id')
                                                        ->hiddenLabel()
                                                        ->placeholder('Provinsi, Kab/Kota, Kecamatan')
                                                        ->size(TextEntrySize::Medium)
                                                        ->weight(FontWeight::Medium)
                                                        ->columnSpanFull()
                                                        ->html()
                                                        ->alignLeft()
                                                        ->extraAttributes([
                                                            'class' => 'deskel-profile-section',
                                                            'style' => 'font-weight: 700; font-size: 1.5rem;',
                                                        ])
                                                        ->formatStateUsing(
                                                            function (string $state): string {
                                                                $prov = ucwords(strtolower($this->deskel->dk->kec->kabkota->prov->prov_nama));
                                                                $kabkota = ucwords(strtolower($this->deskel->dk->kec->kabkota->kabkota_nama));
                                                                $kec = ucwords(strtolower($this->deskel->dk->kec->kec_nama));
                                                                return new HtmlString(
                                                                    'Provinsi ' . $prov . '<br/>' . $kabkota . '<br/>' . 'Kecamatan ' . $kec
                                                                );
                                                            }
                                                        ),
                                                ]),
                                            Group::make()
                                                ->extraAttributes([
                                                    'class' => 'p-auto rounded-lg px-12',
                                                    'style' => 'font-weight: 700; font-size: 1.5rem;',
                                                ])
                                                ->schema([
                                                    TextEntry::make('telepon')
                                                        ->hiddenLabel()
                                                        ->placeholder(
                                                            fn (): string => 'Telepon ' . ucwords(
                                                                strtolower($this->deskel->sebutan)
                                                            )
                                                        )
                                                        ->icon('fas-phone')
                                                        ->iconColor('')
                                                        ->size(TextEntrySize::Medium)
                                                        ->weight(FontWeight::Medium)
                                                        ->columnSpanFull()
                                                        ->extraAttributes([
                                                            'class' => 'deskel-profile-section',
                                                            'style' => 'font-weight: 700; font-size: 1.5rem;',
                                                        ])
                                                        ->formatStateUsing(
                                                            function (string $state): string {
                                                                return ucwords(strtolower($state));
                                                            }
                                                        ),
                                                    TextEntry::make('email')
                                                        ->label('Telepon')
                                                        ->hiddenLabel()
                                                        ->placeholder(
                                                            fn (): string => 'Email ' . ucwords(
                                                                strtolower($this->deskel->sebutan)
                                                            )
                                                        )
                                                        ->icon('far-envelope')
                                                        ->iconColor('')
                                                        ->size(TextEntrySize::Medium)
                                                        ->weight(FontWeight::Medium)
                                                        ->columnSpanFull()
                                                        ->alignLeft()
                                                        ->extraAttributes([
                                                            'class' => 'deskel-profile-section',
                                                            'style' => 'font-weight: 700; font-size: 1.5rem;',
                                                        ])
                                                        ->formatStateUsing(
                                                            function (string $state): string {
                                                                return ucwords(strtolower($state));
                                                            }
                                                        ),
                                                ])


                                        ])->extraAttributes([
                                            'class' => 'items-center space-x-20',
                                            'style' => 'justify-content: stretch;'
                                        ]),

                                    ]),
                                TextEntry::make('')
                                    ->hiddenLabel()
                                    ->size(TextEntrySize::Large)
                                    ->weight(FontWeight::SemiBold)
                                    ->default('VISI')
                                    ->alignCenter(),
                                TextEntry::make('visi')
                                    ->size(TextEntrySize::Medium)
                                    ->weight(FontWeight::Medium)
                                    ->placeholder(fn (): string => 'Visi ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : ucwords(strtolower($this->deskel->sebutan))))
                                    ->hiddenLabel()
                                    ->alignCenter()
                                    ->columnSpanFull()
                                    ->columnStart(1),
                                TextEntry::make('')
                                    ->label('Misi')
                                    ->hiddenLabel()
                                    ->size(TextEntrySize::Large)
                                    ->weight(FontWeight::SemiBold)
                                    ->default('MISI')
                                    ->alignCenter(),
                                TextEntry::make('misi')
                                    ->size(TextEntrySize::Medium)
                                    ->weight(FontWeight::Medium)
                                    ->placeholder(fn (): string => 'Misi ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : ucwords(strtolower($this->deskel->sebutan))))
                                    ->hiddenLabel()
                                    ->alignCenter()
                                    ->listWithLineBreaks()
                                    ->separator(';')
                                    ->columnSpanFull()
                                    ->columnStart(1),
                            ]),

                        Tabs::make('Tabs')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Informasi Umum')
                                    ->columns(2)
                                    ->schema([
                                        Group::make()
                                            ->schema([
                                                TextEntry::make('deskel_nama')
                                                    ->placeholder(fn () => 'Nama ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Nama ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_id')
                                                    ->placeholder(fn () => 'Kode ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Kode ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('kodepos')
                                                    ->placeholder(fn () => 'Kode Pos ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Kode Pos ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('thn_bentuk')
                                                    ->placeholder('Tahun Pembentukan')
                                                    ->label('Tahun Pembentukan')
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('dasar_hukum_bentuk')
                                                    ->placeholder('Dasar Hukum Pembentukan')
                                                    ->label('Dasar Hukum Pembentukan')
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('kepala')
                                                    ->placeholder(fn () => 'Kepala ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Kepala ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('alamat')
                                                    ->placeholder(fn () => 'Alamat ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Alamat ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('email')
                                                    ->placeholder(fn () => 'Email ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Email ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('telepon')
                                                    ->placeholder(fn () => 'Telepon ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->label(fn () => 'Telepon ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                            ]),
                                        Group::make([
                                            TextEntry::make('prov_nama')
                                                ->placeholder('Nama Provinsi')
                                                ->label('Nama Provinsi')
                                                ->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                            TextEntry::make('prov_id')
                                                ->placeholder('Kode Provinsi')
                                                ->label('Kode Provinsi')
                                                ->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                            TextEntry::make('kabkota_nama')
                                                ->placeholder('Nama Kabupaten/Kota')
                                                ->label('Nama Kabupaten/Kota')
                                                ->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                            TextEntry::make('kabkota_id')
                                                ->placeholder('Kode Kabupaten/Kota')
                                                ->label('Kode Kabupaten/Kota')
                                                ->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                            TextEntry::make('kec_nama')
                                                ->placeholder('Nama Kecamatan')
                                                ->label('Nama Kecamatan')
                                                ->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                            TextEntry::make('kec_id')
                                                ->placeholder('Kode Kecamatan')
                                                ->label('Kode Kecamatan')
                                                ->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                            // TextEntry::make('luaswilayah')
                                            //     ->placeholder(fn () => 'Luas Wilayah ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            //     ->label(fn () => 'Luas Wilayah ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            //     ->suffix(' m<sup>2</sup>')
                                            //     ->html()->inlineLabel()
                                            //     ->extraAttributes([
                                            //         'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            //     ]),

                                            TextEntry::make('jmlh_pdd')
                                                ->label(fn () => 'Jumlah Penduduk ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                ->suffix(' Jiwa')
                                                ->inlineLabel()
                                                ->placeholder(fn () => 'Jumlah Penduduk ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                        ])
                                    ]),
                                Tab::make('Batas Wilayah')
                                    ->schema([
                                        TextEntry::make('bts_utara')
                                            ->label(fn () => 'Batas Utara ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->placeholder(fn () => 'Batas Utara ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                        TextEntry::make('bts_timur')
                                            ->label(fn () => 'Batas Timur ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->placeholder(fn () => 'Batas Timur ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                        TextEntry::make('bts_selatan')
                                            ->label(fn () => 'Batas Selatan ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->placeholder(fn () => 'Batas Selatan ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                        TextEntry::make('bts_barat')
                                            ->label(fn () => 'Batas Barat ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->placeholder(fn () => 'Batas Barat ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                    ]),
                                Tab::make('Sejarah')
                                    ->schema([
                                        TextEntry::make('sejarah')
                                            ->label(fn () => 'Sejarah ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->placeholder(fn () => 'Sejarah ' . ($this->deskel->sebutan === null ? 'Desa/Kelurahan' : $this->deskel->sebutan))
                                            ->inlineLabel()
                                            ->markdown()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                    ])
                            ]),

                    ]),

            ]);
    }

    protected function getForms(): array
    {
        return [
            'deskelForm',
        ];
    }

    public function submit(): void
    {
        try {
            $data = $this->deskelForm->getState();
            unset($data['prov_id'], $data['kabkota_id'], $data['kec_id']);

            $datalama = DesaKelurahanProfile::find($this->deskel->id);

            if ($datalama) {
                $datalama->update($data);

                Notification::make()
                    ->title('Data ' . $this->deskel->sebutan . $this->deskel->deskel_nama . ' berhasil diperbarui')
                    ->body('Silahkan cek kembali data yang telah diperbarui.')
                    ->success()
                    ->seconds(5)
                    ->persistent()
                    ->send();

                $this->redirect(route('filament.admin.pages.deskel-profile'));
            } else {
                DesaKelurahanProfile::create($data);

                Notification::make()
                    ->title('Data ' . $this->deskel->sebutan . $this->deskel->deskel_nama . ' berhasil ditambahkan')
                    ->body('Silahkan cek kembali data yang telah ditambahkan.')
                    ->success()
                    ->seconds(5)
                    ->persistent()
                    ->send();

                $this->redirect(route('filament.admin.pages.deskel-profile'));
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}