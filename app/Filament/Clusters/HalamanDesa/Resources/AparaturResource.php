<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Enums\Kependudukan\AgamaType;
use App\Enums\Kependudukan\JenisKelaminType;
use App\Enums\Kependudukan\PendidikanType;
use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource\Pages;
use App\Models\Deskel\Aparatur;
use App\Models\Deskel\Jabatan;
use App\Models\Penduduk;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AparaturResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Aparatur::class;

    protected static ?string $navigationIcon = 'fas-user-tie';

    protected static ?string $navigationLabel = 'Aparatur';

    protected static ?string $slug = 'aparatur';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = HalamanDesa::class;

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
        ];
    }

    public static function form(Form $form): Form
    {
        $isPenduduk = fn (Get $get) => $get('is_penduduk') === 'terdata';

        return $form
            ->schema([
                Grid::make([
                    'default' => 2,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 3,
                    'xl' => 4,
                    '2xl' => 5,
                ])->schema([
                    Section::make('Informasi Aparatur')
                        ->schema([
                            Group::make([
                                FileUpload::make('foto')
                                    ->hiddenLabel()
                                    ->extraAttributes([
                                        'class' => 'justify-center',
                                    ])
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend('gambar-aparatur-'),
                                    )
                                    ->disk('public')
                                    ->directory('deskel/aparatur')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '9:16',
                                        '3:4',
                                        '1:1',
                                    ])
                                    ->downloadable()
                                    ->imagePreviewHeight('300')
                                    ->loadingIndicatorPosition('right')
                                    ->panelAspectRatio('2:3')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('right')
                                    ->uploadProgressIndicatorPosition('left')
                                    ->columnSpan(1),
                                Group::make([
                                    Group::make([
                                        ToggleButtons::make('status_pegawai')
                                            ->inline()
                                            ->grouped()
                                            ->options([
                                                'aktif' => 'Aktif',
                                                'tidak-aktif' => 'Tidak Aktif',
                                            ])
                                            ->default('aktif')
                                            ->required(),
                                        ToggleButtons::make('is_penduduk')
                                            ->inline()
                                            ->hiddenOn('edit')
                                            ->live()
                                            ->label('Terdata pada data penduduk?')
                                            ->grouped()
                                            ->options([
                                                'terdata' => 'Terdata',
                                                'tidak-terdata' => 'Tidak Terdata',
                                            ])
                                            ->default('tidak-terdata')
                                            ->afterStateUpdated(function (ToggleButtons $component, $state) {
                                                if ($state === 'terdata') {
                                                    return $component
                                                        ->getContainer()
                                                        ->getParentComponent()
                                                        ->getContainer()
                                                        ->getComponent('dynamic-nik')
                                                        ->getChildComponentContainer()
                                                        ->fill();
                                                } else {
                                                    return $component;
                                                }
                                            })

                                            ->required(),
                                    ])->columns(2),
                                    Select::make('nik')
                                        ->key('dynamic-nik')
                                        ->inlineLabel()
                                        ->hiddenOn('edit')
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get) => $get('is_penduduk') === 'terdata')
                                        ->label('NIK Penduduk')
                                        ->options(function () {
                                            return Penduduk::pluck('nama_lengkap', 'nik');
                                        })
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(
                                            function (Get $get, Set $set) {
                                                $penduduk = Penduduk::where('nik', $get('nik'))->first();
                                                if (!$penduduk) {
                                                    return;
                                                }

                                                $data = [
                                                    'nama' => $penduduk->nama_lengkap,
                                                    'jenis_kelamin' => $penduduk->jenis_kelamin,
                                                    'pendidikan' => $penduduk->pendidikan,
                                                    'tempat_lahir' => $penduduk->tempat_lahir,
                                                    'tanggal_lahir' => Carbon::parse($penduduk->tanggal_lahir)->format('Y-m-d'),
                                                    'agama' => $penduduk->agama,
                                                ];

                                                foreach ($data as $key => $value) {
                                                    $set($key, $value);
                                                }
                                            }
                                        )
                                        ->searchable()
                                        ->preload(),
                                    TextInput::make('niap')
                                        ->inlineLabel()
                                        ->label('NIAP/NIPD')
                                        ->minLength(18)
                                        ->required(fn (Get $get): bool => !filled($get('nip')))
                                        ->live()
                                        ->placeholder('NIAP'),
                                    TextInput::make('nip')
                                        ->inlineLabel()
                                        ->label('NIP')
                                        ->reactive()
                                        ->minLength(18)
                                        ->required(fn (Get $get): bool => !filled($get('niap')))
                                        ->placeholder('NIP'),
                                    Select::make('jabatan_id')
                                        ->inlineLabel()
                                        ->options(
                                            fn () => Jabatan::pluck('nama', 'id')
                                        )
                                        ->required(),
                                ])->columnSpan(2),
                            ])->columns(3),
                            Group::make([
                                Hidden::make('slug')
                                    ->default(fn () => 'd-' . Aparatur::latest()->first()?->id . time()),
                                TextInput::make('nama')
                                    ->disabled($isPenduduk)
                                    ->required(),
                                Select::make('pendidikan')
                                    ->disabled($isPenduduk)
                                    ->options(PendidikanType::class)
                                    ->required(),
                                Select::make('jenis_kelamin')
                                    ->disabled($isPenduduk)
                                    ->options(JenisKelaminType::class)
                                    ->required(),
                                TextInput::make('tempat_lahir')
                                    ->disabled($isPenduduk)
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->disabled($isPenduduk)
                                    ->format('d-M-Y')
                                    ->required(),
                                Select::make('agama')
                                    ->disabled($isPenduduk)
                                    ->options(AgamaType::class)
                                    ->required(),
                                TextInput::make('pangkat_golongan'),
                                TextInput::make('no_kep_pengangkatan')
                                    ->label('Nomor Keputusan Pengangkatan'),
                                DatePicker::make('tgl_kep_pengangkatan')
                                    ->label('Tanggal Keputusan Pengangkatan'),
                                TextInput::make('no_kep_pemberhentian')
                                    ->label('Nomor Keputusan Pemberhentian'),
                                DatePicker::make('tgl_kep_pemberhentian')
                                    ->label('Tanggal Keputusan Pemberhentian')
                                    ->format('d-M-Y'),
                                TextInput::make('keterangan')
                                    ->placeholder('Keterangan'),

                            ]),

                        ])

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex()
                    ->weight(FontWeight::Bold)
                    ->prefix('')
                    ->suffix('. ')
                    ->sortable()
                    ->grow(false),
                ImageColumn::make('foto')
                    ->alignCenter()
                    ->size(72)
                    ->checkFileExistence(false)
                    ->defaultImageUrl(fn (Aparatur $record) => strtolower($record->jenis_kelamin) === 'laki-laki' ? url('/images/user-man.png') : url('/images/user-woman.png'))
                    ->grow(false),
                TextColumn::make('nama')
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (Aparatur $record) => 'NIAP/NIPD: ' . $record->niap ?? 'NIP: ' . $record->nip)
                    ->alignment(Alignment::Left)
                    ->searchable(),
                TextColumn::make('jabatan.nama')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('pangkat_golongan')
                    ->label('Pangkat/Golongan')
                    ->placeholder('Belum Diisi')
                    ->weight(FontWeight::SemiBold),
                TextColumn::make('jenis_kelamin')
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center),
                IconColumn::make('status_pegawai')
                    ->label('Status')
                    ->alignment(Alignment::Center)
                    ->boolean(),
                TextColumn::make('pendidikan')
                    ->weight(FontWeight::SemiBold)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignment(Alignment::Center),
                TextColumn::make('tempat_lahir')
                    ->weight(FontWeight::SemiBold)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignment(Alignment::Center),
                TextColumn::make('tanggal_lahir')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-M-Y');
                    }),
                TextColumn::make('agama')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center),
                TextColumn::make('keterangan')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center)
                    ->limit(50),
                TextColumn::make('no_kep_pengangkatan')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('No. Kep. Pengangkatan')
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center),
                TextColumn::make('tgl_kep_pengangkatan')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tgl. Kep. Pengangkatan')
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-M-Y');
                    }),
                TextColumn::make('no_kep_pemberhentian')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('No. Kep. Pemberhentian')
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center),
                TextColumn::make('tgl_kep_pemberhentian')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tgl. Kep. Pemberhentian')
                    ->weight(FontWeight::SemiBold)
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-M-Y');
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button()->iconSize(IconSize::Small)->size(ActionSize::Small),
                Tables\Actions\DeleteAction::make()->button()->iconSize(IconSize::Small)->size(ActionSize::Small),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAparaturs::route('/'),
            'create' => Pages\CreateAparatur::route('/create'),
            'edit' => Pages\EditAparatur::route('/{record}/edit'),
        ];
    }
}
