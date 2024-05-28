<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Enums\Desa\TipologiType;
use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource\RelationManagers;
use App\Models\Deskel\{DesaKelurahanProfile};
use App\Models\{DesaKelurahan,  KabKota, Kecamatan, Provinsi};
use App\Settings\GeneralSettings;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\{FileUpload, Grid as GridForm, Group as GroupForm, Repeater, RichEditor, Section as SectionForm, Select, Split as SplitForm, Tabs as TabsForm, Tabs\Tab as Tab, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Grid as GridInfo;
use Filament\Infolists\Components\Group as GroupInfo;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as SectionInfo;
use Filament\Infolists\Components\Split as SplitInfo;
use Filament\Infolists\Components\Tabs as TabsInfo;
use Filament\Infolists\Components\Tabs\Tab as TabInfo;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DeskelProfileResource extends Resource
{
    protected static ?string $model = DesaKelurahanProfile::class;

    protected static ?string $navigationIcon = 'fas-house-chimney';

    protected static ?string $navigationLabel = 'Profil Desa/Kelurahan';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'profil';

    public static function form(Form $form): Form
    {
        $settings = app(GeneralSettings::class)->toArray();
        return $form
            ->schema([
                GridForm::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 3,
                    'xl' => 4,
                    '2xl' => 5,
                ])
                    ->schema([
                        SectionForm::make([
                            FileUpload::make('gambar')
                                ->label('Gambar Kantor Desa/Kelurahan')
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                        ->prepend('kantor-'),
                                )
                                ->disk('public')
                                ->directory('deskel/profil')
                                ->visibility('public')
                                ->preserveFilenames()
                                ->moveFiles()
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    null,
                                    '16:9',
                                    '4:3',
                                    '1:1',
                                ])
                                ->imageResizeMode('contain')
                                ->imagePreviewHeight('200')
                                ->loadingIndicatorPosition('left')
                                ->panelAspectRatio('3:1')
                                ->panelLayout('integrated')
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeTargetWidth('1920')
                                ->imageResizeTargetHeight('1080')
                                ->removeUploadedFileButtonPosition('right')
                                ->uploadButtonPosition('left')
                                ->uploadProgressIndicatorPosition('left')
                                ->downloadable()
                                ->openable()
                        ])->columnSpanFull(),
                        TabsForm::make('tabs')
                            ->tabs([
                                Tab::make('Identitas Desa/Kelurahan')
                                    ->schema([
                                        GridForm::make()
                                            ->columns(3)
                                            ->schema([
                                                GroupForm::make([
                                                    FileUpload::make('logo')
                                                        ->label('Gambar Logo Desa/Kelurahan')
                                                        ->getUploadedFileNameForStorageUsing(
                                                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                ->prepend('logo-'),
                                                        )
                                                        ->directory('deskel/profil')
                                                        ->visibility('public')
                                                        ->moveFiles()
                                                        ->image()
                                                        ->imageEditor()
                                                        ->imageEditorAspectRatios([
                                                            null,
                                                            '16:9',
                                                            '4:3',
                                                            '1:1',
                                                        ])
                                                        ->alignCenter()
                                                        ->preserveFilenames()
                                                        ->loadingIndicatorPosition('center bottom')
                                                        ->imageResizeMode('contain')
                                                        ->imagePreviewHeight('200')
                                                        ->panelAspectRatio('1.5:1')
                                                        ->panelLayout('integrated')
                                                        ->removeUploadedFileButtonPosition('right')
                                                        ->uploadButtonPosition('left')
                                                        ->uploadProgressIndicatorPosition('left')
                                                        ->downloadable()
                                                        ->openable(),
                                                ])->columnSpan(1),
                                                GroupForm::make(
                                                    fn (Model $record) => is_null($record->deskel_id) ?
                                                        [
                                                            Select::make('prov_id')
                                                                ->label('Provinsi')
                                                                ->placeholder('Pilih Provinsi')
                                                                ->searchable()
                                                                ->native(false)
                                                                ->required()
                                                                ->options(Provinsi::pluck('prov_nama', 'prov_id'))
                                                                ->live()
                                                                ->dehydrated(),
                                                            Select::make('kabkota_id')
                                                                ->label('Kab/Kota')
                                                                ->placeholder('Pilih Kab/Kota')
                                                                ->searchable()
                                                                ->native(false)
                                                                ->required()
                                                                ->options(
                                                                    fn (Get $get): Collection => KabKota::query()->where('prov_id', $get('prov_id'))->pluck('kabkota_nama', 'kabkota_id')
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
                                                                    fn (Get $get): Collection => Kecamatan::query()->where('kabkota_id', $get('kabkota_id'))->pluck('kec_nama', 'kec_id')
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
                                                                    fn (Get $get): Collection => DesaKelurahan::query()->where('kec_id', $get('kec_id'))->pluck('deskel_nama', 'deskel_id')
                                                                )
                                                                ->live()
                                                                ->dehydrated(),
                                                        ] : [
                                                            Select::make('prov_id')
                                                                ->label('Provinsi')
                                                                ->placeholder('Pilih Provinsi')
                                                                ->relationship('prov', 'prov_nama')
                                                                ->searchable()
                                                                ->native(false)
                                                                ->required()
                                                                ->options(Provinsi::pluck('prov_nama', 'prov_id'))
                                                                ->live()
                                                                ->dehydrated(),
                                                            Select::make('kabkota_id')
                                                                ->label('Kab/Kota')
                                                                ->placeholder('Pilih Kab/Kota')
                                                                ->relationship('kabkota', 'kabkota_nama')
                                                                ->searchable()
                                                                ->native(false)
                                                                ->required()
                                                                ->options(
                                                                    fn (Get $get): Collection => KabKota::query()->where('prov_id', $get('prov_id'))->pluck('kabkota_nama', 'kabkota_id')
                                                                )
                                                                ->dehydrated()
                                                                ->live()
                                                                ->preload(),
                                                            Select::make('kec_id')
                                                                ->label('Kecamatan')
                                                                ->relationship('kec', 'kec_nama')
                                                                ->placeholder('Pilih Kecamatan')
                                                                ->searchable()
                                                                ->native(false)
                                                                ->required()
                                                                ->options(
                                                                    fn (Get $get): Collection => Kecamatan::query()->where('kabkota_id', $get('kabkota_id'))->pluck('kec_nama', 'kec_id')
                                                                )
                                                                ->dehydrated()
                                                                ->live()
                                                                ->preload(),
                                                            Select::make('deskel_id')
                                                                ->label('Desa/Kelurahan')
                                                                ->relationship('dk', 'deskel_nama')
                                                                ->searchable()
                                                                ->placeholder('Pilih Desa/Kelurahan')
                                                                ->unique(DesaKelurahanProfile::class, 'deskel_id', ignoreRecord: true)
                                                                ->native(false)
                                                                ->required()
                                                                ->options(
                                                                    fn (Get $get): Collection => DesaKelurahan::query()->where('kec_id', $get('kec_id'))->pluck('deskel_nama', 'deskel_id')
                                                                )
                                                                ->live()
                                                                ->dehydrated(),
                                                        ]
                                                )->columnSpan(2)
                                            ]),

                                        TextInput::make('kodepos')
                                            ->label(fn () => 'Kode Pos ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Kode Pos ' . $settings['sebutan_deskel'])
                                            ->inlineLabel()
                                            ->autofocus(),
                                        TextInput::make('thn_bentuk')
                                            ->label(fn () => 'Tahun Pembentukan ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Tahun Pembentukan ' . $settings['sebutan_deskel'])
                                            ->inlineLabel()
                                            ->numeric()
                                            ->minValue(0)
                                            ->autofocus(),
                                        Select::make('dasar_hukum_id')
                                            ->label(fn () => 'Dasar Hukum ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Pilih Dokumen Dasar Hukum ' . $settings['sebutan_deskel'])
                                            ->relationship('dokumen', 'dok_nama')
                                            ->inlineLabel()
                                            ->nullable()
                                            ->autofocus(),
                                        TextInput::make('koordinat_lat')
                                            ->label(fn () => 'Longitude ' . $settings['sebutan_deskel'])
                                            ->inlineLabel()
                                            ->placeholder(fn () => 'Luas Wilayah ' . $settings['sebutan_deskel'])
                                            ->nullable(),
                                        TextInput::make('koordinat_lat')
                                            ->label(fn () => 'Lattitude ' . $settings['sebutan_deskel'])
                                            ->inlineLabel()
                                            ->placeholder(fn () => 'Luas Wilayah ' . $settings['sebutan_deskel'])
                                            ->nullable(),
                                        Textarea::make('alamat')
                                            ->label(fn () => 'Alamat Kantor ' . $settings['sebutan_deskel'])
                                            ->inlineLabel()
                                            ->autofocus()
                                            ->placeholder(fn () => 'Alamat Kantor ' . $settings['sebutan_deskel']),
                                    ])->columns(1),
                                Tab::make('Data Umum Desa/Kelurahan ')
                                    ->schema([
                                        Select::make('tipologi')
                                            ->label(fn () => 'Tipologi ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Tipologi ' . $settings['sebutan_deskel'])
                                            ->options(TipologiType::class),
                                        GroupForm::make()
                                            ->columns(3)
                                            ->schema([
                                                TextInput::make('luas_total')
                                                    ->label(fn () => 'Luas Wilayah ' . $settings['sebutan_deskel'])
                                                    ->placeholder('Luas Wilayah')
                                                    ->nullable()
                                                    ->columnSpan(1)
                                                    ->live()
                                                    ->suffix('Ha'),
                                                Repeater::make('luaswilayah')
                                                    ->hiddenLabel()
                                                    ->live()
                                                    ->defaultItems(1)
                                                    ->addable(false)
                                                    ->reorderable(false)
                                                    ->deletable(false)
                                                    ->columnSpan(2)
                                                    ->schema([
                                                        GridForm::make([
                                                            'default' => 2,
                                                            'sm' => 2,
                                                            'md' => 2,
                                                        ])
                                                            ->schema([
                                                                TextInput::make('lahan_sawah')
                                                                    ->label('Lahan Sawah')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->placeholder('Luas Lahan Sawah')
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->suffix('Ha'),
                                                                TextInput::make('lahan_ladang')
                                                                    ->label('Lahan Ladang')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->placeholder('Luas Lahan Ladang')
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->suffix('Ha'),
                                                                TextInput::make('lahan_perkebunan')
                                                                    ->label('Lahan Perkebunan')
                                                                    ->placeholder('Luas Lahan Perkebunan')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->suffix('Ha'),
                                                                TextInput::make('lahan_peternakan')
                                                                    ->label('Lahan Peternakan')
                                                                    ->placeholder('Luas Lahan Peternakan')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->suffix('Ha'),
                                                                TextInput::make('lahan_hutan')
                                                                    ->label('Lahan Hutan')
                                                                    ->placeholder('Luas Lahan Hutan')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->suffix('Ha'),
                                                                TextInput::make('waduk_danau_situ')
                                                                    ->label('Luas Waduk/Danau/Situ')
                                                                    ->placeholder('Luas Waduk/Danau/Situ')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->suffix('Ha'),
                                                                TextInput::make('lainnya')
                                                                    ->label('Lahan Lainnya')
                                                                    ->placeholder('Luas Lahan Lainnya')
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(
                                                                        function (Get $get, Set $set) {
                                                                            $set('../../luas_total', ($get('lahan_ladang') + $get('lahan_sawah') + $get('lahan_perkebunan') + $get('lahan_peternakan') + $get('lahan_hutan') + $get('waduk_danau_situ') + $get('lainnya')));
                                                                        }
                                                                    )
                                                                    ->nullable()
                                                                    ->numeric()
                                                                    ->inputMode('decimal')
                                                                    ->columnSpanFull()
                                                                    ->suffix('Ha'),
                                                            ]),
                                                    ]),
                                            ]),
                                        TextInput::make('jmlh_sert_tanah')
                                            ->label(fn () => 'Jumlah Tanah Bersertifikat ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Jumlah Tanah Bersertifikat ' . $settings['sebutan_deskel'])
                                            ->nullable()
                                            ->numeric()
                                            ->minValue(0)
                                            ->inputMode('decimal')
                                            ->suffix('Sertifikat'),
                                        TextInput::make('tanah_kas')
                                            ->label(fn () => 'Tanah Kas ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Tanah Kas Desa/Kelurahan ' . $settings['sebutan_deskel'])
                                            ->nullable()
                                            ->minValue(0)
                                            ->numeric()
                                            ->inputMode('decimal')
                                            ->suffix('Ha'),
                                        Repeater::make('orbitrasi')
                                            ->label(fn () => 'Orbitrasi ' . $settings['sebutan_deskel'] . ' (Jarak dari Pusat Pemerintahan)')
                                            ->addable(false)
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->schema([
                                                GridForm::make([
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
                                                            ->minValue(0)
                                                            ->inputMode('decimal')
                                                            ->suffix('Km'),
                                                        TextInput::make('pusat_pemerintah')
                                                            ->label('Jarak dari Pusat Pemerintahan Kab/Kota')
                                                            ->placeholder('Jarak')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->minValue(0)
                                                            ->inputMode('decimal')
                                                            ->suffix('Km'),
                                                        TextInput::make('pusat_kab')
                                                            ->label('Jarak dari Kota/Ibukota Kabupaten')
                                                            ->placeholder('Jarak')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->minValue(0)
                                                            ->inputMode('decimal')
                                                            ->suffix('Km'),
                                                        TextInput::make('pusat_prov')
                                                            ->label('Jarak dari Pusat Pemerintahan Provinsi')
                                                            ->placeholder('Jarak')
                                                            ->nullable()
                                                            ->numeric()
                                                            ->minValue(0)
                                                            ->inputMode('decimal')
                                                            ->suffix('Km'),
                                                    ])
                                            ]),
                                        TextInput::make('kantor')
                                            ->label(fn () => 'Kantor ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Kantor ' . $settings['sebutan_deskel'])
                                            ->nullable(),
                                    ]),
                                Tab::make('Sejarah Desa/Kelurahan')
                                    ->schema([
                                        TextInput::make('bts_utara')
                                            ->inlineLabel()
                                            ->label(fn () => 'Batas Utara ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Batas Utara ' . $settings['sebutan_deskel']),
                                        TextInput::make('bts_timur')
                                            ->inlineLabel()
                                            ->label(fn () => 'Batas Timur ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Batas Timur ' . $settings['sebutan_deskel']),
                                        TextInput::make('bts_selatan')
                                            ->inlineLabel()
                                            ->label(fn () => 'Batas Selatan ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Batas Selatan ' . $settings['sebutan_deskel']),
                                        TextInput::make('bts_barat')
                                            ->inlineLabel()
                                            ->label(fn () => 'Batas Barat ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Batas Barat ' . $settings['sebutan_deskel']),

                                        Textarea::make('visi')
                                            ->label(fn () => 'Visi ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Visi ' . $settings['sebutan_deskel']),
                                        RichEditor::make('misi')
                                            ->label(fn () => 'Misi ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Misi ' . $settings['sebutan_deskel']),
                                        RichEditor::make('sejarah')
                                            ->label(fn () => 'Sejarah ' . $settings['sebutan_deskel'])->nullable()
                                            ->placeholder(fn () => 'Sejarah ' . $settings['sebutan_deskel']),
                                    ]),
                                Tab::make('Kontak dan Media Desa/Kelurahan')
                                    ->schema([
                                        TextInput::make('telepon')
                                            ->label(fn () => 'Telepon ' . $settings['sebutan_deskel'])->nullable()->tel()->nullable()
                                            ->placeholder(fn () => 'Telepon ' . $settings['sebutan_deskel']),
                                        TextInput::make('email')
                                            ->label(fn () => 'Email ' . $settings['sebutan_deskel'])->nullable()->email()
                                            ->placeholder(fn () => 'Email ' . $settings['sebutan_deskel']),
                                        TextInput::make('website')
                                            ->label(fn () => 'Website ' . $settings['sebutan_deskel'])->nullable()->url()
                                            ->placeholder(fn () => 'Website ' . $settings['sebutan_deskel']),
                                        TextInput::make('facebook')
                                            ->label(fn () => 'Facebook ' . $settings['sebutan_deskel'])->nullable()->url()
                                            ->placeholder(fn () => 'Facebook ' . $settings['sebutan_deskel']),
                                        TextInput::make('twitter')
                                            ->label(fn () => 'Twitter ' . $settings['sebutan_deskel'])->nullable()->url()
                                            ->placeholder(fn () => 'Twitter ' . $settings['sebutan_deskel']),
                                        TextInput::make('instagram')
                                            ->label(fn () => 'Instagram ' . $settings['sebutan_deskel'])->nullable()->url()
                                            ->placeholder(fn () => 'Instagram ' . $settings['sebutan_deskel']),
                                        TextInput::make('youtube')
                                            ->label(fn () => 'Youtube ' . $settings['sebutan_deskel'])->nullable()->url()
                                            ->placeholder(fn () => 'Youtube ' . $settings['sebutan_deskel']),
                                    ]),
                            ])->columnSpanFull(),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        $settings = app(GeneralSettings::class)->toArray();
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['prov', 'kabkota', 'kec', 'dk']))
            ->columns([
                Split::make([
                    TextColumn::make('No')
                        ->rowIndex()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large)
                        ->prefix('')
                        ->suffix('.    ')
                        ->sortable()
                        ->searchable()
                        ->grow(false),
                    ImageColumn::make('logo')
                        ->alignCenter()
                        ->circular()
                        ->size(72)
                        ->checkFileExistence(false)
                        ->defaultImageUrl(
                            fn (DesaKelurahanProfile $record) => $record->getLogo()
                        )
                        ->grow(false),
                    TextColumn::make('dk.deskel_nama')
                        ->weight(FontWeight::SemiBold)
                        ->prefix(ucwords(strtolower($settings['sebutan_deskel'])) . ' ')
                        ->placeholder('Nama ' . ucwords(strtolower($settings['sebutan_deskel'])))
                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                        ->size(TextColumnSize::Large)
                        ->alignment(Alignment::Left)
                        ->searchable()
                        ->sortable(),
                    Stack::make([
                        TextColumn::make('prov.prov_nama')
                            ->placeholder('Nama ' . ucwords(strtolower($settings['sebutan_prov'])))
                            ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                            ->size(TextColumnSize::Medium)
                            ->weight(FontWeight::Medium)
                            ->prefix('Provinsi: ')
                            ->inline(),
                        TextColumn::make('kabkota.kabkota_nama')
                            ->placeholder('Nama ' . ucwords(strtolower($settings['sebutan_kabkota'])))
                            ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                            ->size(TextColumnSize::Medium)
                            ->weight(FontWeight::Medium)
                            ->prefix('Kabupaten/Kota: ')
                            ->inline(),
                        TextColumn::make('kec.kec_nama')
                            ->placeholder('Nama ' . ucwords(strtolower($settings['sebutan_kec'])))
                            ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                            ->size(TextColumnSize::Medium)
                            ->weight(FontWeight::Medium)
                            ->prefix('Kecamatan: ')
                    ]),
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconSize(IconSize::Small)->button(),
                Tables\Actions\ViewAction::make()->iconSize(IconSize::Small)->button()->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {

        $settings = app(GeneralSettings::class)->toArray();
        return $infolist
            ->schema([
                GridInfo::make([
                    'sm' => 3,
                    'lg' => 3,
                ])
                    ->schema([
                        SectionInfo::make('Gambar  ')
                            ->heading('')
                            ->extraAttributes(
                                function ($record) {
                                    return [
                                        'class' => 'h-80 w-full',
                                        'style' => 'background-image: url(' . ($record->getGambar()) . '); 
                                        background-size: cover; background-position: center; background-repeat: no-repeat; border-radius: 0px; border-top-right-radius: 0.5rem; border-top-left-radius: 0.5rem;',
                                    ];
                                }
                            ),
                        SectionInfo::make('Info Desa/Kelurahan')
                            ->heading('')
                            ->extraAttributes([
                                'class' => '-mt-6',
                                'style' => 'border-radius: 0px; border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;',
                            ])
                            ->columnSpanFull()
                            ->schema([
                                GroupInfo::make()
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
                                        SplitInfo::make([
                                            ImageEntry::make('logo')
                                                ->hiddenLabel()
                                                ->circular()
                                                ->columnSpan(1)
                                                ->size(150)
                                                ->visibility('public')
                                                ->defaultImageUrl(url('images/logo.png'))
                                                ->extraAttributes([
                                                    'class' => 'justify-center',
                                                ])
                                                ->extraImgAttributes([
                                                    'alt' => 'Logo',
                                                    'loading' => 'lazy',
                                                    'class' => ' border-4 border-gray bg-white dark:bg-info-950 p-2',
                                                    'style' => 'object-fit: contain;',
                                                ]),
                                            GroupInfo::make()
                                                ->columnSpan(2)
                                                ->schema([
                                                    TextEntry::make('dk.deskel_nama')
                                                        ->size(TextEntrySize::Large)
                                                        ->color('white')
                                                        ->alignCenter()
                                                        ->placeholder(
                                                            fn (): string => 'Nama ' . ucwords(strtolower($settings['sebutan_deskel']))
                                                        )
                                                        ->hiddenLabel()
                                                        ->weight(FontWeight::Bold)
                                                        ->prefix(
                                                            fn (): string => ucwords(strtolower(($settings['sebutan_deskel'])) . ' ')
                                                        )
                                                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                                                        ->extraAttributes([
                                                            'class' => 'deskel-profile-section text-white',
                                                        ])
                                                        ->columnSpanFull(),
                                                    TextEntry::make('prov_id')
                                                        ->hiddenLabel()
                                                        ->size(TextEntrySize::Small)
                                                        ->color('white')
                                                        ->placeholder('Provinsi, Kab/Kota, Kecamatan')
                                                        ->weight(FontWeight::Medium)
                                                        ->getStateUsing(
                                                            fn ($record) => new HtmlString(
                                                                $settings['singkatan_prov'] . " " . ucwords(strtolower($record->prov?->prov_nama)) . '<br/>' . $settings['singkatan_kabkota'] . " " . ucwords(strtolower($record->kabkota->kabkota_nama)) . '<br/>' . " " . $settings['singkatan_kec'] . ucwords(strtolower($record->kec->kec_nama))
                                                            )
                                                        )
                                                        ->columnSpanFull()
                                                        ->html()
                                                        ->alignCenter()
                                                        ->extraAttributes([
                                                            'class' => 'deskel-profile-section text-white -mt-6',
                                                        ])
                                                ]),
                                            GroupInfo::make()
                                                ->extraAttributes([
                                                    'class' => 'p-auto rounded-lg px-12',
                                                    'style' => 'font-weight: 700; font-size: 1.5rem;',
                                                ])
                                                ->schema([
                                                    TextEntry::make('telepon')
                                                        ->hiddenLabel()
                                                        ->placeholder(fn (): string => 'Telepon ' . ucwords(strtolower($settings['sebutan_deskel'])))
                                                        ->icon('fas-phone')
                                                        ->iconColor('white')
                                                        ->size(TextEntrySize::Medium)
                                                        ->weight(FontWeight::Medium)
                                                        ->color('white')
                                                        ->columnSpanFull()
                                                        ->extraAttributes(['class' => 'deskel-profile-section text-white flex justify-center md:justify-start'])
                                                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state))),
                                                    TextEntry::make('email')
                                                        ->hiddenLabel()
                                                        ->placeholder(fn (): string => 'Email ' . ucwords(strtolower($settings['sebutan_deskel'])))
                                                        ->icon('far-envelope')
                                                        ->iconColor('white')
                                                        ->size(TextEntrySize::Medium)
                                                        ->weight(FontWeight::Medium)
                                                        ->color('white')
                                                        ->columnSpanFull()
                                                        ->extraAttributes(['class' => 'deskel-profile-section text-white flex justify-center md:justify-start'])
                                                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state))),
                                                ])
                                        ])
                                            ->verticalAlignment(VerticalAlignment::Center)
                                            ->from('md')
                                            ->extraAttributes([
                                                'style' => 'justify-content: stretch;'
                                            ]),
                                    ]),
                                TextEntry::make('')
                                    ->hiddenLabel()
                                    ->size(TextEntrySize::Large)
                                    ->weight(FontWeight::SemiBold)
                                    ->default('VISI')
                                    ->extraAttributes([])
                                    ->alignCenter(),
                                TextEntry::make('visi')
                                    ->size(TextEntrySize::Medium)
                                    ->weight(FontWeight::Medium)
                                    ->placeholder(fn (): string => 'Visi ' . (ucwords(strtolower($settings['sebutan_deskel'])) ?: 'Desa/Kelurahan'))
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
                                    ->extraAttributes([])
                                    ->alignCenter(),
                                TextEntry::make('misi')
                                    ->size(TextEntrySize::Medium)
                                    ->weight(FontWeight::Medium)
                                    ->placeholder(fn (): string => 'Misi ' . (ucwords(strtolower($settings['sebutan_deskel'])) ?: 'Desa/Kelurahan'))
                                    ->hiddenLabel()
                                    ->alignCenter()
                                    ->listWithLineBreaks()
                                    ->separator(';')
                                    ->columnSpanFull()
                                    ->columnStart(1),
                            ]),

                        TabsInfo::make('Tabs')
                            ->columnSpanFull()
                            ->tabs([
                                TabInfo::make('Identitas Desa/Kelurahan')
                                    ->columns(2)
                                    ->schema([
                                        GroupInfo::make()
                                            ->schema([
                                                TextEntry::make('dk.deskel_nama')
                                                    ->placeholder(fn () => 'Nama ' . $settings['sebutan_deskel'])
                                                    ->label(fn () => 'Nama ' . $settings['sebutan_deskel'])
                                                    ->inlineLabel(),
                                                TextEntry::make('deskel_id')
                                                    ->placeholder(fn () => 'Kode ' . $settings['sebutan_deskel'])
                                                    ->label(fn () => 'Kode ' . $settings['sebutan_deskel'])
                                                    ->inlineLabel(),
                                                TextEntry::make('kodepos')
                                                    ->placeholder(fn () => 'Kode Pos ' . $settings['sebutan_deskel'])
                                                    ->label(fn () => 'Kode Pos ' . $settings['sebutan_deskel'])
                                                    ->inlineLabel(),
                                                TextEntry::make('thn_bentuk')
                                                    ->placeholder('Tahun Pembentukan')
                                                    ->label('Tahun Pembentukan')
                                                    ->inlineLabel(),
                                                TextEntry::make('dokumen.dok_nama')
                                                    ->placeholder('Dasar Hukum Pembentukan')
                                                    ->label('Dasar Hukum Pembentukan')
                                                    ->inlineLabel(),
                                                TextEntry::make('kepala')
                                                    ->placeholder(fn () => 'Kepala ' . $settings['sebutan_deskel'])
                                                    ->label(fn () => 'Kepala ' . $settings['sebutan_deskel'])
                                                    ->inlineLabel(),
                                                TextEntry::make('alamat')
                                                    ->placeholder(fn () => 'Alamat ' . $settings['sebutan_deskel'])
                                                    ->label(fn () => 'Alamat ' . $settings['sebutan_deskel'])
                                                    ->inlineLabel(),
                                                TextEntry::make('jmlh_pdd')
                                                    ->label(fn () => 'Jumlah Penduduk ' . $settings['sebutan_deskel'])
                                                    ->suffix(' Jiwa')
                                                    ->inlineLabel()
                                                    ->placeholder(fn () => 'Jumlah Penduduk ' . $settings['sebutan_deskel']),
                                            ]),
                                        GroupInfo::make([
                                            TextEntry::make('prov.prov_nama')
                                                ->placeholder('Nama Provinsi')
                                                ->label('Nama Provinsi')
                                                ->inlineLabel(),
                                            TextEntry::make('prov.prov_id')
                                                ->placeholder('Kode Provinsi')
                                                ->label('Kode Provinsi')
                                                ->inlineLabel(),
                                            TextEntry::make('kabkota.kabkota_nama')
                                                ->placeholder('Nama Kabupaten/Kota')
                                                ->label('Nama Kabupaten/Kota')
                                                ->inlineLabel(),
                                            TextEntry::make('kabkota.kabkota_id')
                                                ->placeholder('Kode Kabupaten/Kota')
                                                ->label('Kode Kabupaten/Kota')
                                                ->inlineLabel(),
                                            TextEntry::make('kec.kec_nama')
                                                ->placeholder('Nama Kecamatan')
                                                ->label('Nama Kecamatan')
                                                ->inlineLabel(),
                                            TextEntry::make('kec.kec_id')
                                                ->placeholder('Kode Kecamatan')
                                                ->label('Kode Kecamatan')
                                                ->inlineLabel(),
                                            TextEntry::make('koordinat_lat')
                                                ->placeholder(fn () => 'Koordinat Lattitude ' . $settings['sebutan_deskel'])
                                                ->label(fn () => 'Koordinat Lat ' . $settings['sebutan_deskel'])
                                                ->inlineLabel(),
                                            TextEntry::make('koordinat_long')
                                                ->placeholder(fn () => 'Koordinat Longitude ' . $settings['sebutan_deskel'])
                                                ->label(fn () => 'Koordinat Longitude ' . $settings['sebutan_deskel'])
                                                ->inlineLabel(),
                                        ])
                                    ]),
                                TabInfo::make('Data Umum Desa/Kelurahan')
                                    ->schema([
                                        TextEntry::make('tipologi')
                                            ->label(fn () => 'Tipologi ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Tipologi ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        RepeatableEntry::make('luaswilayah')
                                            ->label('Luas Wilayah')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('lahan_sawah')
                                                    ->label('Lahan Sawah')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Lahan Sawah')
                                                    ->inlineLabel(),
                                                TextEntry::make('lahan_ladang')
                                                    ->label('Lahan Ladang')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Lahan Ladang')
                                                    ->inlineLabel(),
                                                TextEntry::make('lahan_perkebunan')
                                                    ->label('Lahan Perkebunan')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Lahan Perkebunan')
                                                    ->inlineLabel(),
                                                TextEntry::make('lahan_peternakan')
                                                    ->label('Lahan Peternakan')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Lahan Peternakan')
                                                    ->inlineLabel(),
                                                TextEntry::make('lahan_hutan')
                                                    ->label('Lahan Hutan')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Lahan Hutan')
                                                    ->inlineLabel(),
                                                TextEntry::make('waduk_danau_situ')
                                                    ->label('Luas Waduk/Danau/Situ')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Waduk/Danau/Situ')
                                                    ->inlineLabel(),
                                                TextEntry::make('lainnya')
                                                    ->label('Lahan Lainnya')
                                                    ->suffix(' Ha')
                                                    ->numeric()
                                                    ->placeholder('Luas Lahan Lainnya')
                                                    ->inlineLabel(),
                                            ]),
                                        TextEntry::make('jmlh_sert_tanah')
                                            ->label('Jumlah Tanah Bersertifikat')
                                            ->suffix(' Sertifikat')
                                            ->numeric()
                                            ->placeholder('Jumlah Tanah Bersertifikat')
                                            ->inlineLabel(),

                                        TextEntry::make('tanah_kas')
                                            ->label('Tanah Kas')
                                            ->suffix(' Ha')
                                            ->numeric()
                                            ->placeholder('Luas Tanah Kas')
                                            ->inlineLabel(),

                                        RepeatableEntry::make('orbitrasi')
                                            ->label('Orbitrasi (Jarak dari Pusat Pemerintahan)')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('pusat_kec')
                                                    ->label('Jarak dari Pusat Pemerintahan Kecamatan')
                                                    ->suffix(' Km')
                                                    ->numeric()
                                                    ->placeholder('Jarak dari Pusat Pemerintahan Kecamatan')
                                                    ->inlineLabel(),
                                                TextEntry::make('pusat_kota')
                                                    ->label('Jarak dari Pusat Pemerintahan Kab/Kota')
                                                    ->suffix(' Km')
                                                    ->numeric()
                                                    ->placeholder('Jarak dari Pusat Pemerintahan Kab/Kota')
                                                    ->inlineLabel(),
                                                TextEntry::make('pusat_kab')
                                                    ->label('Jarak dari Kota/Ibukota Kabupaten')
                                                    ->suffix(' Km')
                                                    ->numeric()
                                                    ->placeholder('Jarak dari Kota/Ibukota Kabupaten')
                                                    ->inlineLabel(),
                                                TextEntry::make('pusat_prov')
                                                    ->label('Jarak dari Pusat Pemerintahan Provinsi')
                                                    ->suffix(' Km')
                                                    ->numeric()
                                                    ->placeholder('Jarak dari Pusat Pemerintahan Provinsi')
                                                    ->inlineLabel(),
                                            ]),

                                    ]),
                                TabInfo::make('Sejarah Desa/Kelurahan')
                                    ->schema([
                                        TextEntry::make('sejarah')
                                            ->label(fn () => 'Sejarah ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Sejarah ' . $settings['sebutan_deskel'])
                                            ->inlineLabel()
                                            ->markdown(),
                                        TextEntry::make('bts_utara')
                                            ->label(fn () => 'Batas Utara ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Batas Utara ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('bts_timur')
                                            ->label(fn () => 'Batas Timur ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Batas Timur ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('bts_selatan')
                                            ->label(fn () => 'Batas Selatan ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Batas Selatan ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('bts_barat')
                                            ->label(fn () => 'Batas Barat ' . $settings['sebutan_deskel'])
                                            ->placeholder(fn () => 'Batas Barat ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                    ]),
                                TabInfo::make('Kontak Desa/Kelurahan')
                                    ->schema([
                                        TextEntry::make('email')
                                            ->placeholder(fn () => 'Email ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Email ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('telepon')
                                            ->placeholder(fn () => 'Telepon ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Telepon ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('website')
                                            ->placeholder(fn () => 'Website ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Website ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('facebook')
                                            ->placeholder(fn () => 'Facebook ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Facebook ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('twitter')
                                            ->placeholder(fn () => 'Twitter ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Twitter ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('instagram')
                                            ->placeholder(fn () => 'Instagram ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Instagram ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                        TextEntry::make('youtube')
                                            ->placeholder(fn () => 'Youtube ' . $settings['sebutan_deskel'])
                                            ->label(fn () => 'Youtube ' . $settings['sebutan_deskel'])
                                            ->inlineLabel(),
                                    ])
                            ]),

                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeskelProfiles::route('/'),
            'view' => Pages\ViewDeskelProfile::route('/{record}'),
            'edit' => Pages\EditDeskelProfile::route('/{record}/edit'),
        ];
    }
}
