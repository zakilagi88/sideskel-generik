<?php

namespace App\Filament\Clusters\HalamanPotensi\Resources;

use App\Filament\Clusters\HalamanPotensi;
use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource\Pages;
use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource\RelationManagers;
use App\Models\Desa\PotensiSDA as ModelPotensiSDA;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;


class PotensiSDAResource extends Resource
{
    protected static ?string $model = ModelPotensiSDA::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = HalamanPotensi::class;

    protected static ?string $slug = 'sda';

    protected static bool $shouldRegisterNavigation = false;



    public static function form(Form $form): Form
    {
        $extraAttributes = [
            'class' => ' bg-white rounded-2xl shadow-md pb-4 mb-4',
        ];
        return $form
            ->schema([
                TextInput::make('jenis')
                    ->required()
                    ->live()
                    ->placeholder('Jenis Potensi SDA'),
                Repeater::make('data')
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->schema(
                        [
                            TextInput::make('extra')
                                ->hidden(
                                    fn (Get $get) => (($get('../../jenis') === 'sumber-daya-air')
                                        && ($get('label') === 'Sungai') || ($get('label') === 'Rawa') || ($get('label') === 'Pemanfaatan Danau/Waduk/Situ'))
                                        ? false : true
                                )
                                ->label(
                                    fn (Get $get) => ($get('label') === 'Pemanfaatan Danau/Waduk/Situ') ? 'Luas ' . $get('label') : 'Jumlah ' . $get('label')
                                )
                                ->numeric()
                                ->suffix(
                                    fn (Get $get) => ($get('label') === 'Sungai') ? 'Buah' : 'Ha'
                                )
                                ->inlineLabel(true)
                                ->placeholder(
                                    fn (Get $get) => ($get('label') === 'Pemanfaatan Danau/Waduk/Situ') ? 'Luas ' . $get('label') : 'Jumlah ' . $get('label')
                                )
                                ->required(),
                            TableRepeater::make('entitas')
                                ->label(
                                    function (Get $get) {
                                        return ucwords($get('label'));
                                    }
                                )
                                ->columnSpanFull()
                                ->extraAttributes($extraAttributes)
                                ->addAction(
                                    function (Action $action) {
                                        return $action
                                            ->label('Tambah Data')
                                            ->action(
                                                function (array $arguments, Repeater $component): void {
                                                    $newUuid = $component->generateUuid();
                                                    $items = $component->getState();

                                                    if (empty($items)) {
                                                        $items[$newUuid] = [];
                                                    } else {
                                                        $keys = array_keys($items[array_key_first($items)]);

                                                        ksort($keys);
                                                        $items[$newUuid] = array_fill_keys($keys, '');

                                                        $items = array_map(function ($key) {
                                                            ksort($key);
                                                            return $key;
                                                        }, $items);
                                                    }

                                                    $component->state($items);
                                                    $component->getChildComponentContainer($newUuid)->fill();
                                                    $component->collapsed(false, shouldMakeComponentCollapsible: false);
                                                    $component->callAfterStateUpdated();
                                                }
                                            );
                                    }
                                )
                                ->headers(
                                    function (Get $get) {
                                        $input = [];
                                        $data = (self::extraTextInputs());
                                        $getJenis = $get('../../jenis');
                                        $label = $get('label');

                                        $dataKeys = collect($data[$getJenis])
                                            ->filter(function ($key) use ($label) {
                                                return $key['label'] === $label;
                                            })
                                            ->map(function ($key) {
                                                return $key['entitas'];
                                            })->values()->first();

                                        foreach ($dataKeys as $key => $value) {
                                            $input[] = Header::make($key)
                                                ->label(fn () => preg_replace('/^\d+\s/', '', ucwords(str_replace('_', ' ', $key))))
                                                ->align(Alignment::Center);
                                        }

                                        return $input;
                                    }
                                )->schema(
                                    function (Get $get) {
                                        $input = [];
                                        $data = (self::extraTextInputs());
                                        $getJenis = $get('../../jenis');
                                        $label = $get('label');


                                        $dataKeys = collect($data[$getJenis])
                                            ->filter(function ($key) use ($label) {
                                                return $key['label'] === $label;
                                            })
                                            ->map(function ($key) {
                                                return $key['entitas'];
                                            })->values()->first();

                                        foreach ($dataKeys as $key => $value) {
                                            // Jika nilai numeric dan memiliki suffix, tambahkan ke input sebagai TextInput
                                            $input[] = self::generateInputs($key, $value);
                                        }

                                        return $input;
                                    }
                                ),


                        ]
                    ),
            ]);
    }

    public static function generateInputs($key, $value)
    {
        $isNumeric = isset($value['numeric']) ? (bool)$value['numeric'] : false;
        $normalizedName = preg_replace('/^\d+\s/', '', ucwords(str_replace('_', ' ', $key)));

        if ($value['select'] ?? false) {
            $component = Forms\Components\Select::make($key)
                ->hiddenLabel()
                ->options($value['options']);
        } else if ($value['checkbox'] ?? false) {
            $component = Forms\Components\Checkbox::make($key)
                ->extraAttributes([
                    'class' => 'mx-auto',
                ])
                ->hiddenLabel();
        } else if ($value['checkbox-list'] ?? false) {
            $component = Forms\Components\CheckboxList::make($key)
                ->hiddenLabel()
                ->options($value['options'])
                ->columns($value['columns'] ?? 1);
        } else {
            $component = Forms\Components\TextInput::make($key)
                ->hiddenLabel()
                ->numeric($isNumeric)
                ->validationAttribute($normalizedName)
                ->suffix($value['suffix'] ?? null)
                ->inlineLabel($value['inline'] ?? false)
                ->placeholder('Masukkan ' . $normalizedName)
                ->minValue(0);
        }

        return $component;
    }

    public static function extraTextInputs(): array
    {
        return [
            'pertanian-perkebunan' => [
                [
                    'label' => 'Luas Tanaman Pangan Menurut Komoditas',
                    'entitas' => [
                        '0 Nama Komoditas' => [],
                        '1 Luas (Ha)' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Hasil Panen' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Ha',
                        ],
                    ],
                ],
                [
                    'label' => 'Jenis Komoditas Buah Buahan yang dibudidayakan',
                    'entitas' => [
                        '0 Nama Komoditas' => [],
                        '1 Luas (Ha)' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Hasil Panen' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Ha',
                        ],
                    ],
                ],
                [
                    'label' => 'Tanaman Apotik Hidup Dan Sejenisnya',
                    'entitas' => [
                        '0 Nama Tanaman' => [],
                        '1 Luas (Ha)' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Hasil Panen' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Ha',
                        ],
                    ],
                ],

                [
                    'label' => 'Luas dan hasil perkebunan menurut Jenis Komoditas',
                    'entitas' => [
                        '0 Jenis Komoditas' => [],
                        '1 Luas (Swasta/Negara)' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Hasil Panen (Swasta/Negara)' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Ha',
                        ],
                        '3 Luas (Rakyat)' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '4 Hasil Panen (Rakyat)' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Ha',
                        ],
                    ],
                ]

            ],
            'kehutanan' => [
                [
                    'label' => 'Hasil Hutan',
                    'entitas' => [
                        '0 Nama Komoditas' => [],
                        '1 Hasil Panen' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Satuan' => [
                            'select' => true,
                            'options' => ['Ton/Ha' => 'Ton/Ha', 'Kwintal/Ha' => 'Kwintal/Ha', 'Kg/Ha' => 'Kg/Ha', 'Kwintal' => 'Kwintal', 'Kg' => 'Kg', 'Ton' => 'Ton']
                        ],
                    ],
                ],
                [
                    'label' => 'Kondisi Hutan',
                    'entitas' => [
                        '0 Jenis Hutan' => [],
                        '1 Kondisi Baik' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Kondisi Rusak' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '3 Total' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                    ],
                ],
                [
                    'label' => 'Dampak yang Timbul dari Pengolahan Hutan',
                    'entitas' => [
                        '0 Jenis Dampak' => [],
                        '1 Dampak' => [
                            'checkbox' => true,
                        ],
                    ],
                ],
            ],
            'peternakan' => [
                [
                    'label' => 'Jumlah Populasi Ternak Menurut Jenis Ternak',
                    'entitas' => [
                        '0 Jenis Ternak' => [],
                        '1 Jumlah Populasi' => [
                            'numeric' => true,
                            'suffix' => 'Orang',
                        ],
                        '2 Perkiraan Jumlah Populasi' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Ha',
                        ],
                    ],
                ],
                [
                    'label' => 'Produksi Peternakan',
                    'entitas' => [
                        '0 Jenis Produksi' => [],
                        '1 Hasil Produksi' => [
                            'numeric' => true,
                            'suffix' => 'Ekor',
                        ],
                        '2 Satuan' => [
                            'select' => true,
                            'options' => ['Kg/Thn' => 'Kg/Thn', 'M/Thn' => 'M/Thn', 'Lt/Thn' => 'Lt/Thn', 'Unit/Thn' => 'Unit/Thn']
                        ],

                    ],
                ],
                [
                    'label' => 'Ketersediaan Hijauan Pakan Ternak',
                    'entitas' => [
                        '0 Keterangan' => [],
                        '1 Jumlah' => [
                            'numeric' => true,
                            'suffix' => 'Ekor',
                        ],
                        '2 Satuan' => [
                            'select' => true,
                            'options' => ['Ha' => 'Ha', 'Ton/Ha' => 'Ton/Ha', 'Ton' => 'Ton']
                        ],
                    ],
                ],
                [
                    'label' => 'Pemilik Usaha Pengolahan Hasil Ternak',
                    'entitas' => [
                        '0 Jenis Usaha' => [],
                        '1 Jumlah Pemilik Usaha' => [
                            'numeric' => true,
                            'suffix' => 'Orang',
                        ],
                    ],
                ],
                [
                    'label' => 'Ketersediaan lahan pemeliharaan ternak/padang penggembalaan',
                    'entitas' => [
                        '0 Jumlah Kepemilikan Lahan' => [],
                        '1 Luas' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                    ],
                ]
            ],
            'perikanan' => [
                [
                    'label' => 'Jenis dan Alat Produksi Budidaya Ikan Laut dan Payau',
                    'entitas' => [
                        '0 Jenis Alat' => [],
                        '1 Jumlah' => [
                            'numeric' => true,
                        ],
                        '2 Satuan' => [
                            'select' => true,
                            'options' => ['Unit' => 'Unit', 'Ha' => 'Ha']
                        ],
                        '3 Hasil Produksi' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Tahun',
                        ],
                    ],
                ],
                [
                    'label' => 'Jenis dan Sarana Produksi Budidaya Ikan Air Tawar',
                    'entitas' => [
                        '0 Jenis Sarana' => [],
                        '1 Jumlah' => [
                            'numeric' => true,
                        ],
                        '2 Satuan' => [
                            'select' => true,
                            'options' => ['Unit' => 'Unit', 'm2' => 'm2']
                        ],
                        '3 Hasil Produksi' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Tahun',
                        ],
                    ],
                ],
                [
                    'label' => 'Jenis Ikan dan Produksi',
                    'entitas' => [
                        '0 Jenis Ikan' => [],
                        '1 Hasil Produksi' => [
                            'numeric' => true,
                            'suffix' => 'Ton/Tahun',
                        ],
                    ],
                ],

            ],
            'bahan-galian' => [
                [
                    'label' => 'Jenis, deposit dan kepemilikan bahan galian',
                    'entitas' => [
                        '0 Jenis Bahan Galian' => [],
                        '1 Keberadaan' => [
                            'checkbox' => true,
                        ],
                        '2 Skala Produksi' => [
                            'checkbox-list' => true,
                            'options' => ['Kecil' => 'Kecil', 'Sedang' => 'Sedang', 'Besar' => 'Besar'],
                        ],
                        '3 Kepemilikan' => [
                            'checkbox-list' => true,
                            'options' => ['Negara' => 'Negara', 'Swasta' => 'Swasta', 'Perorangan' => 'Perorangan', 'Adat' => 'Adat', 'Lainnya' => 'Lainnya'],
                        ],
                    ],
                ],
            ],
            'sumber-daya-air' => [
                [
                    'label' => 'Potensi Air dan Sumber Daya Air',
                    'entitas' => [
                        '0 Jenis Sumber Air' => [],
                        '1 Debit Volume' => [
                            'checkbox-list' => true,
                            'options' => ['Kecil' => 'Kecil', 'Sedang' => 'Sedang', 'Besar' => 'Besar'],
                        ],
                    ],
                ],
                [
                    'label' => 'Sumber dan Kualitas Air Bersih',
                    'entitas' => [
                        '0 Jenis' => [],
                        '1 Jumlah Unit' => [
                            'numeric' => true,
                            'suffix' => 'Unit',
                        ],
                        '2 Kondisi Rusak' => [
                            'numeric' => true,
                            'suffix' => 'Unit',
                        ],
                        '3 Pemanfaatan' => [
                            'numeric' => true,
                            'suffix' => 'KK',
                        ],
                        '4 Kualitas' => [
                            'checkbox-list' => true,
                            'options' => ['Berbau' => 'Berbau', 'Berasa' => 'Berasa', 'Berwarna' => 'Berwarna', 'Baik' => 'Baik'],
                        ],
                    ],
                ],
                [
                    'label' => 'Sungai',
                    'extra' => 'Jumlah Sungai',
                    'entitas' => [
                        '0 Kondisi' => [],
                        '1 Keterangan' => [
                            'checkbox' => true,
                        ],
                    ],
                ],
                [
                    'label' => 'Rawa',
                    'extra' => 'Jumlah Rawa',
                    'entitas' => [
                        '0 Pemanfaatan' => [],
                        '1 Keterangan' => [
                            'checkbox' => true,
                        ],
                    ],
                ],
                [
                    'label' => 'Pemanfaatan Danau/Waduk/Situ',
                    'extra' => 'Jumlah Danau/Waduk/Situ',
                    'entitas' => [
                        '0 Kondisi' => [],
                        '1 Keterangan' => [
                            'checkbox' => true,
                        ],
                    ],
                ],
                [
                    'label' => 'Kondisi Danau/Waduk/Situ',
                    'extra' => 'Jumlah Danau/Waduk/Situ',
                    'entitas' => [
                        '0 Kondisi' => [],
                        '1 Keterangan' => [
                            'checkbox' => true,
                        ],
                    ],
                ],
                [
                    'label' => 'Air Panas',
                    'entitas' => [
                        '0 Sumber' => [],
                        '1 Jumlah Lokasi' => [
                            'numeric' => true,
                            'suffix' => 'Lokasi',
                        ],
                        '2 Pemanfaatan Wisata' => [],
                        '3 Kepemilikian/Pengelolaan' => [
                            'numeric' => true,
                            'suffix' => 'Orang',
                        ]
                    ],
                ]

            ],
            'udara' => [
                [
                    'label' => 'Kualitas Udara',
                    'entitas' => [
                        '0 Sumber' => [],
                        '1 Jumlah Lokasi' => [
                            'numeric' => true,
                            'suffix' => 'Lokasi',
                        ],
                        '2 Polutan' => [],
                        '3 Efek terhadap Kesehatan' => [],
                        '4 Kepemilikian' => [],
                    ],
                ]
            ],
            'kebisingan' => [
                [
                    'label' => 'Kebisingan',
                    'entitas' => [
                        '0 Tingkat Kebisingan' => [],
                        '1 Ekses Dampak Kebisingan' => [
                            'checkbox' => true,
                        ],
                        '2 Sumber Kebisingan' => [],
                        '3 Efek terhadap Penduduk' => [],
                    ],
                ]
            ],
            'ruang-publik' => [
                [
                    'label' => 'Ruang Publik/Taman',
                    'entitas' => [
                        '0 Ruang Publik/Taman' => [],
                        '1 Keberadaan' => [
                            'checkbox' => true,
                        ],
                        '2 Luas' => [
                            'numeric' => true,
                            'suffix' => 'M2',
                        ],
                        '3 Tingkat Pemanfaatan' => [
                            'select' => true,
                            'options' => ['Aktif' => 'Aktif', 'Pasif' => 'Pasif', 'Tidak Aktif' => 'Tidak Aktif'],
                        ],
                    ],
                ]
            ],
            'wisata' => [
                [
                    'label' => 'Jenis dan Jumlah Wisata',
                    'entitas' => [
                        '0 Lokasi Tempat/Area Wisata' => [],
                        '1 Luas' => [
                            'numeric' => true,
                            'suffix' => 'Ha',
                        ],
                        '2 Tingkat Pemanfaatan' => [
                            'select' => true,
                            'options' => ['Aktif' => 'Aktif', 'Pasif' => 'Pasif', 'Tidak Aktif' => 'Tidak Aktif'],
                        ],
                    ],
                ]
            ]
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    IconColumn::make('jenis')
                        ->size(IconColumnSize::TwoExtraLarge)
                        ->grow(false)
                        ->color(fn (string $state): string => match ($state) {
                            'pertanian-perkebunan' => 'success',
                            'kehutanan' => 'success',
                            'peternakan' => 'danger',
                            'perikanan' => 'info',
                            'bahan-galian' => 'primary',
                            'sumber-daya-air' => 'secondary',
                            'udara' => 'warning',
                            'kebisingan' => 'danger',
                            'ruang-publik' => 'info',
                            'wisata' => 'warning',
                            default => 'gray',
                        })
                        ->icon(fn (string $state): string => match ($state) {
                            'pertanian-perkebunan' => 'fas-seedling',
                            'kehutanan' => 'fas-tree',
                            'peternakan' => 'fas-cow',
                            'perikanan' => 'fas-fish',
                            'bahan-galian' => 'fas-gem',
                            'sumber-daya-air' => 'fas-water',
                            'udara' => 'fas-wind',
                            'kebisingan' => 'fas-volume-up',
                            'ruang-publik' => 'fas-map-marked',
                            'wisata' => 'fas-umbrella-beach',
                            default => 'heroicon-o-information-circle',
                        }),
                    TextColumn::make('jenis')
                        ->verticallyAlignCenter()
                        ->alignment(Alignment::Left)
                        ->weight(FontWeight::Bold)
                        ->formatStateUsing(function ($state) {
                            return ucwords(str_replace('-', ' ', $state));
                        })
                        ->searchable()
                        ->sortable(),
                ]),

                Panel::make([
                    Split::make([
                        TextColumn::make('data')
                            ->getStateUsing(function ($record) {
                                return self::getLabelsByJenis($record);
                            })
                            ->listWithLineBreaks()
                            ->bulleted()
                    ])->from('md'),
                ])->collapsible()

            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getLabelsByJenis($record)
    {
        $labels = array_map(function ($key) {
            if (isset($key['label'])) {
                return ucwords(str_replace('_', ' ', $key['label']));
            }
        }, $record->data);

        return $labels;
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
            'index' => Pages\ListPotensiSDAS::route('/'),
            'create' => Pages\CreatePotensiSDA::route('/create'),
            'edit' => Pages\EditPotensiSDA::route('/{record}/edit'),
        ];
    }
}