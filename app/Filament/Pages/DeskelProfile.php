<?php

namespace App\Filament\Pages;

use App\Models\{DesaKelurahan, DesaKelurahanProfile, KabKota, Kecamatan, Penduduk, Provinsi};
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\{FileUpload, RichEditor, Select, Split as SplitForm, Tabs as ComponentsTabs, Tabs\Tab as TabsTab, Textarea, TextInput, ToggleButtons};
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
            'deskel_sebutan' => $this->deskel->deskel_sebutan ?? null,
            'deskel_nama' => $this->deskel->deskel_nama ?? null,
            'deskel_gambar' => $this->deskel->deskel_gambar ?? null,
            'deskel_id' => $this->deskel->deskel_id ?? null,
            'deskel_nama' => $this->deskel->dk->deskel_nama ?? null,
            'deskel_kepala' => $this->deskel->dk->deskel_kepala ?? null,
            'deskel_tipe' => $this->deskel->deskel_tipe ?? null,
            'deskel_alamat' => $this->deskel->deskel_alamat ?? null,
            'deskel_kodepos' => $this->deskel->deskel_kodepos ?? null,
            'deskel_luaswilayah' => $this->deskel->deskel_luaswilayah ?? null,
            'deskel_thn_pembentukan' => $this->deskel->deskel_thn_pembentukan ?? null,
            'deskel_dasar_hukum_pembentukan' => $this->deskel->deskel_dasar_hukum_pembentukan ?? null,
            'deskel_jumlahpenduduk' => $this->pdd ?? null,
            'deskel_batas_utara' => $this->deskel->deskel_batas_utara ?? null,
            'deskel_batas_timur' => $this->deskel->deskel_batas_timur ?? null,
            'deskel_batas_selatan' => $this->deskel->deskel_batas_selatan ?? null,
            'deskel_batas_barat' => $this->deskel->deskel_batas_barat ?? null,
            'deskel_visi' => $this->deskel->deskel_visi ?? null,
            'deskel_misi' => $this->deskel->deskel_misi ?? null,
            'deskel_sejarah' => $this->deskel->deskel_sejarah ?? null,
            'deskel_logo' => $this->deskel->deskel_logo ?? asset('images/logo-deskel.png'),
            'deskel_telepon' => $this->deskel->deskel_telepon ?? null,
            'deskel_email' => $this->deskel->deskel_email ?? null,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            'edit' =>
            ActionsAction::make('edit')
                ->icon('heroicon-o-pencil')
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
        return $form
            ->schema([
                SplitForm::make([
                    FileUpload::make('deskel_gambar')
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
                    FileUpload::make('deskel_logo')
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
                        TabsTab::make('Informasi Umum Desa/Kelurahan')
                            ->schema([
                                TextInput::make('deskel_sebutan')
                                    ->label('Sebutan Desa/Kelurahan')->placeholder('Masukkan Sebutan untuk Desa/Kelurahan')->hint('Desa / Kelurahan / Nagari / Lainnya')->live(onBlur: true),
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

                                TextInput::make('deskel_kodepos')
                                    ->label(fn (Get $get) => 'Kode Pos ' . $get('deskel_sebutan'))
                                    ->placeholder(fn (Get $get) => 'Kode Pos ' . $get('deskel_sebutan'))->autofocus()->required(),
                                TextInput::make('deskel_thn_pembentukan')
                                    ->label(fn (Get $get) => 'Tahun Pembentukan ' . $get('deskel_sebutan'))->numeric()->minValue(0)
                                    ->placeholder(fn (Get $get) => 'Tahun Pembentukan ' . $get('deskel_sebutan'))->autofocus()->required(),
                                TextInput::make('deskel_dasar_hukum_pembentukan')
                                    ->label(fn (Get $get) => 'Dasar Hukum ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Dasar Hukum ' . $get('deskel_sebutan'))->autofocus()->required(),
                                Select::make('deskel_kepala')
                                    ->label(fn (Get $get) => 'Kepala ' . $get('deskel_sebutan'))
                                    ->placeholder(fn (Get $get) => 'Kepala ' . $get('deskel_sebutan')),
                                Textarea::make('deskel_alamat')
                                    ->label(fn (Get $get) => 'Alamat Kantor ' . $get('deskel_sebutan'))->autofocus()->required()
                                    ->placeholder(fn (Get $get) => 'Alamat Kantor ' . $get('deskel_sebutan')),
                                TextInput::make('deskel_luaswilayah')
                                    ->label(fn (Get $get) => 'Luas Wilayah ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Luas Wilayah ' . $get('deskel_sebutan'))->suffix('m2'),
                                TextInput::make('deskel_jumlahpenduduk')
                                    ->label(fn (Get $get) => 'Jumlah Penduduk ' . $get('deskel_sebutan'))->nullable()->disabled()
                                    ->placeholder(fn (Get $get) => 'Jumlah Penduduk ' . $get('deskel_sebutan')),
                            ]),
                        TabsTab::make('Batas Desa/Kelurahan')
                            ->schema([
                                TextInput::make('deskel_batas_utara')
                                    ->label(fn (Get $get) => 'Batas Utara ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Batas Utara ' . $get('deskel_sebutan')),
                                TextInput::make('deskel_batas_timur')
                                    ->label(
                                        fn (Get $get) => 'Batas Timur ' . $get('deskel_sebutan')
                                    )->nullable()
                                    ->placeholder(fn (Get $get) => 'Batas Timur ' . $get('deskel_sebutan')),
                                TextInput::make('deskel_batas_selatan')
                                    ->label(fn (Get $get) => 'Batas Selatan ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Batas Selatan ' . $get('deskel_sebutan')),
                                TextInput::make('deskel_batas_barat')
                                    ->label(fn (Get $get) => 'Batas Barat ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Batas Barat ' . $get('deskel_sebutan')),
                            ]),
                        TabsTab::make('Sejarah Desa/Kelurahan')
                            ->schema([
                                Textarea::make('deskel_visi')
                                    ->label(fn (Get $get) => 'Visi ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Visi ' . $get('deskel_sebutan')),
                                RichEditor::make('deskel_misi')
                                    ->label(fn (Get $get) => 'Misi ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Misi ' . $get('deskel_sebutan')),
                                RichEditor::make('deskel_sejarah')
                                    ->label(fn (Get $get) => 'Sejarah ' . $get('deskel_sebutan'))->nullable()
                                    ->placeholder(fn (Get $get) => 'Sejarah ' . $get('deskel_sebutan')),
                            ]),
                        TabsTab::make('Kontak Desa/Kelurahan')
                            ->schema([
                                TextInput::make('deskel_telepon')
                                    ->label(fn (Get $get) => 'Telepon ' . $get('deskel_sebutan'))->nullable()->tel()->nullable()
                                    ->placeholder(fn (Get $get) => 'Telepon ' . $get('deskel_sebutan')),
                                TextInput::make('deskel_email')
                                    ->label(fn (Get $get) => 'Email ' . $get('deskel_sebutan'))->nullable()->email()
                                    ->placeholder(fn (Get $get) => 'Email ' . $get('deskel_sebutan')),
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
                                'style' => 'background-image: url(' . ($this->deskel->deskel_gambar ? asset('storage/' . $this->deskel->deskel_gambar) : asset('images/bg-kantor.png')) . '); background-size: cover; background-position: center; background-repeat: no-repeat; border-radius: 0px; border-top-right-radius: 0.5rem; border-top-left-radius: 0.5rem;',


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
                                            ImageEntry::make('deskel_logo')
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
                                                            fn (): string => 'Nama ' . ucwords(strtolower($this->deskel->deskel_sebutan))
                                                        )
                                                        ->hiddenLabel()
                                                        ->prefix(
                                                            fn (): string => ucwords(strtolower(($this->deskel->deskel_sebutan)) . ' ')
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
                                                    TextEntry::make('deskel_telepon')
                                                        ->hiddenLabel()
                                                        ->placeholder(
                                                            fn (): string => 'Telepon ' . ucwords(
                                                                strtolower($this->deskel->deskel_sebutan)
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
                                                    TextEntry::make('deskel_email')
                                                        ->label('Telepon')
                                                        ->hiddenLabel()
                                                        ->placeholder(
                                                            fn (): string => 'Email ' . ucwords(
                                                                strtolower($this->deskel->deskel_sebutan)
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
                                TextEntry::make('deskel_visi')
                                    ->size(TextEntrySize::Medium)
                                    ->weight(FontWeight::Medium)
                                    ->placeholder(fn (): string => 'Visi ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : ucwords(strtolower($this->deskel->deskel_sebutan))))
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
                                TextEntry::make('deskel_misi')
                                    ->size(TextEntrySize::Medium)
                                    ->weight(FontWeight::Medium)
                                    ->placeholder(fn (): string => 'Misi ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : ucwords(strtolower($this->deskel->deskel_sebutan))))
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
                                                    ->placeholder(fn () => 'Nama ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Nama ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_id')
                                                    ->placeholder(fn () => 'Kode ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Kode ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_kodepos')
                                                    ->placeholder(fn () => 'Kode Pos ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Kode Pos ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_thn_pembentukan')
                                                    ->placeholder('Tahun Pembentukan')
                                                    ->label('Tahun Pembentukan')
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_dasar_hukum_pembentukan')
                                                    ->placeholder('Dasar Hukum Pembentukan')
                                                    ->label('Dasar Hukum Pembentukan')
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_kepala')
                                                    ->placeholder(fn () => 'Kepala ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Kepala ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_alamat')
                                                    ->placeholder(fn () => 'Alamat ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Alamat ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_email')
                                                    ->placeholder(fn () => 'Email ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Email ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->inlineLabel()
                                                    ->extraAttributes([
                                                        'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                    ]),
                                                TextEntry::make('deskel_telepon')
                                                    ->placeholder(fn () => 'Telepon ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                    ->label(fn () => 'Telepon ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
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
                                            TextEntry::make('deskel_luaswilayah')
                                                ->placeholder(fn () => 'Luas Wilayah ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                ->label(fn () => 'Luas Wilayah ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                ->suffix(' m<sup>2</sup>')
                                                ->html()->inlineLabel()
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),

                                            TextEntry::make('deskel_jumlahpenduduk')
                                                ->label(fn () => 'Jumlah Penduduk ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                ->suffix(' Jiwa')
                                                ->inlineLabel()
                                                ->placeholder(fn () => 'Jumlah Penduduk ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                                ->extraAttributes([
                                                    'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                                ]),
                                        ])
                                    ]),
                                Tab::make('Batas Wilayah')
                                    ->schema([
                                        TextEntry::make('deskel_batas_utara')
                                            ->label(fn () => 'Batas Utara ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->placeholder(fn () => 'Batas Utara ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                        TextEntry::make('deskel_batas_timur')
                                            ->label(fn () => 'Batas Timur ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->placeholder(fn () => 'Batas Timur ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                        TextEntry::make('deskel_batas_selatan')
                                            ->label(fn () => 'Batas Selatan ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->placeholder(fn () => 'Batas Selatan ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                        TextEntry::make('deskel_batas_barat')
                                            ->label(fn () => 'Batas Barat ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->placeholder(fn () => 'Batas Barat ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->inlineLabel()
                                            ->extraAttributes([
                                                'class' => 'border-solid border-gray-400 dark:border-gray-600 border-b pl-2 hover:bg-gray-100',
                                            ]),
                                    ]),
                                Tab::make('Sejarah')
                                    ->schema([
                                        TextEntry::make('deskel_sejarah')
                                            ->label(fn () => 'Sejarah ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
                                            ->placeholder(fn () => 'Sejarah ' . ($this->deskel->deskel_sebutan === null ? 'Desa/Kelurahan' : $this->deskel->deskel_sebutan))
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
                    ->title('Data ' . $this->deskel->deskel_sebutan . $this->deskel->deskel_nama . ' berhasil diperbarui')
                    ->body('Silahkan cek kembali data yang telah diperbarui.')
                    ->success()
                    ->seconds(5)
                    ->persistent()
                    ->send();

                $this->redirect(route('filament.admin.pages.deskel-profile'));
            } else {
                DesaKelurahanProfile::create($data);

                Notification::make()
                    ->title('Data ' . $this->deskel->deskel_sebutan . $this->deskel->deskel_nama . ' berhasil ditambahkan')
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
