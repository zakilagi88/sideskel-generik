<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Facades\Deskel;
use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource\RelationManagers;
use App\Models\Deskel\SaranaPrasarana;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaranaPrasaranaResource extends Resource
{
    protected static ?string $model = SaranaPrasarana::class;

    protected static ?string $navigationIcon = 'fas-list-check';

    protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'sarana-prasarana';

    protected static ?int $navigationSort = 3;



    public static function form(Form $form): Form
    {
        $deskelProfile = Deskel::getFacadeRoot();

        return $form
            ->schema([
                Hidden::make('deskel_profil_id')
                    ->default(
                        fn () => $deskelProfile->id ?? null
                    ),
                TextInput::make('jenis')
                    ->inlineLabel()
                    ->disabledOn('edit')
                    ->required(),
                TableRepeater::make('data')
                    ->hiddenLabel()
                    ->headers([
                        Header::make('Keterangan'),
                        Header::make('Jumlah'),
                        Header::make('Satuan'),
                    ])
                    ->columnSpanFull()
                    ->minItems(1)
                    ->schema(
                        [
                            TextInput::make('nama')
                                ->formatStateUsing(
                                    function ($state) {
                                        return ucwords(str_replace('_', ' ', $state));
                                    }
                                )
                                ->inlineLabel()
                                ->required(),
                            TextInput::make('jumlah')
                                ->inlineLabel()
                                ->numeric()
                                ->required(),
                            Select::make('satuan')
                                ->options(
                                    [
                                        'buah' => 'Buah',
                                        'meter' => 'Meter',
                                        'km' => 'Km',
                                    ]
                                )
                                ->default('buah')
                                ->inlineLabel()
                                ->required(),
                        ]

                    )
            ]);
    }

    public static function generateInputs($state)
    {
        $inputs = [];
        $keys = static::additionalData()[$state];
        foreach ($keys as $key => $value) {
            if (array_key_exists($key, self::getExtraSuffix())) {
                $inputs[] = TextInput::make($key)
                    ->suffix(self::getExtraSuffix()[$key])
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->placeholder('Masukkan ' . $value)
                    ->inlineLabel();
                continue;
            } else {
                $inputs[] = TextInput::make($key)
                    ->suffix('Buah')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->placeholder('Masukkan ' . $value)

                    ->inlineLabel();
            }
        }
        return $inputs;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),
                Panel::make([
                    Split::make([
                        TextColumn::make('data')
                            ->getStateUsing(function ($record) {
                                $labelKeys = array_column($record->data, 'nama');
                                $formattedLabelKeys = array_map(fn ($label) => static::formattedLabel($label), $labelKeys);
                                return $formattedLabelKeys;
                            })
                            ->listWithLineBreaks()
                            ->bulleted()
                    ])->from('md'),
                ])->collapsed(false)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button()->iconSize(IconSize::Small),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function formattedLabel(string $label): string
    {
        return ucwords(str_replace('_', ' ', $label));
    }

    public static function getExtraSuffix()
    {
        return [
            'jalan_desa_kelurahan' => 'Km',
            'jalan_kabupaten' => 'Km',
            'jalan_provinsi' => 'Km',
            'jalan_nasional' => 'Km',
            'saluran_drainase' => 'Meter',
            'saluran_irigasi' => 'Meter',
        ];
    }


    public static function additionalData(): array
    {
        return [
            'kesehatan' =>
            [
                'puskesmas' => 'Puskesmas',
                'puskesmas_pembantu' => 'Puskesmas Pembantu',
                'poskesdes' => 'Poskesdes',
                'posyandu_polindes' => 'Posyandu Dan Polindes',
                'rumah_sakit' => 'Rumah Sakit',
            ],
            'pendidikan' =>
            [
                'perpustakaan' => 'Perpustakaan',
                'gedung_sekolah_paud' => 'Gedung Sekolah PAUD',
                'gedung_sekolah_tk' => 'Gedung Sekolah TK',
                'gedung_sekolah_sd' => 'Gedung Sekolah SD',
                'gedung_sekolah_smp' => 'Gedung Sekolah SMP',
                'gedung_sekolah_sma' => 'Gedung Sekolah SMA',
                'gedung_perguruan_tinggi' => 'GedunG Perguruan Tinggi',
            ],
            'ibadah' =>
            [
                'masjid' => 'Masjid',
                'mushola' => 'Mushola',
                'gereja' => 'Gereja',
                'pura' => 'Pura',
                'vihara' => 'Vihara',
                'klenteng' => 'Klenteng',
            ],
            'umum' =>
            [
                'olahraga' => 'Olahraga',
                'kesenian_budaya' => 'Kesenian/Budaya',
                'balai_pertemuan' => 'Balai Pertemuan',
                'sumur' => 'Sumur',
                'pasar' => 'Pasar',
                'lainnya' => 'Lainnya',
            ],
            'transportasi' =>
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
            'air_bersih' =>
            [
                'hidran_air' => 'Hidran Air',
                'penampung_air_hujan' => 'Penampung Air Hujan',
                'pamsimas' => 'Pamsimas',
                'pengolahan_air_bersih' => 'Pengolahan Air Bersih',
                'sumur_gali' => 'Sumur Gali',
                'sumur_pompa' => 'Sumur Pompa',
                'tangki_air_bersih' => 'Tangki Air Bersih',
            ],
            'sanitasi_irigasi' =>
            [
                'mck_umum' => 'MCK Umum',
                'jamban_keluarga' => 'Jamban Keluarga',
                'saluran_drainase' => 'Saluran Drainase',
                'pintu_air' => 'Pintu Air',
                'saluran_irigasi' => 'Saluran Irigasi',
            ]

        ];
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
            'index' => Pages\ManageSaranaPrasaranas::route('/'),

        ];
    }
}
