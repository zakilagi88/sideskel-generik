<?php

namespace App\Filament\Resources\WilayahResource\Pages;

use App\Filament\Resources\WilayahResource;
use App\Models\{Dusun, RT, RW, User, Wilayah, KabKota, Kecamatan, Kelurahan, Provinsi};
use Filament\Forms\Components\{Grid, Repeater, Select, TextInput};
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;
use Filament\Actions;
use Filament\Forms\{Get, Set};
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\map;

class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;

    protected $rwData, $rwAkun, $rtAkun, $wilayahData;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Generate')
                ->modalWidth('7xl')
                ->fillForm(self::fillWilayah())
                ->form(self::generateWilayahForm())
                ->successNotification(
                    fn () => Notification::make()
                        ->success()
                        ->title('Generate Wilayah Berhasil')
                        ->body('Silahkan cek data wilayah di menu wilayah.')
                )
                ->action(
                    function (array $data) {
                        DB::beginTransaction();
                        try {

                            switch ($data['type']) {
                                case 'Desa':
                                    self::processDesa($data);
                                    break;
                                case 'Dusun':
                                    self::processDusun($data);
                                    break;
                                default:
                                    break;
                            }

                            DB::commit();
                            $admin = User::whereHas('roles', function ($query) {
                                $query->where('name', 'super_admin');
                            })->get();

                            Notification::make()
                                ->success()
                                ->title('Generate Wilayah Berhasil')
                                ->body('Silahkan cek data wilayah di menu wilayah.')
                                ->sendToDatabase($admin);
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            throw $th;
                        }
                    }
                )
        ];
    }

    public function generateWilayahForm(): array
    {
        return   [
            Grid::make(3)
                ->schema([
                    Select::make('prov_id')
                        ->label('Provinsi')
                        ->native(false)
                        ->options(
                            Provinsi::pluck('prov_nama', 'prov_id')
                        )
                        ->live()
                        ->dehydrated(),
                    Select::make('kabkota_id')
                        ->label('Kab/Kota')
                        ->native(false)
                        ->options(
                            fn (Get $get): Collection => KabKota::query()
                                ->where('prov_id', $get('prov_id'))
                                ->pluck('kabkota_nama', 'kabkota_id')
                        )
                        ->dehydrated()
                        ->live()
                        ->preload(),

                    Select::make('kec_id')
                        ->label('Kecamatan')
                        ->native(false)
                        ->options(
                            fn (Get $get): Collection => Kecamatan::query()
                                ->where('kabkota_id', $get('kabkota_id'))
                                ->pluck('kec_nama', 'kec_id')
                        )
                        ->dehydrated()
                        ->live()
                        ->preload(),
                    Select::make('kel_id')
                        ->label('Desa/Kelurahan')
                        ->native(false)
                        ->options(
                            fn (Get $get): Collection => Kelurahan::query()
                                ->where('kec_id', $get('kec_id'))
                                ->pluck('kel_nama', 'kel_id')
                        )
                        ->live()

                        ->dehydrated(),
                    Select::make('type')
                        ->native(false)
                        ->options([
                            'Desa' => 'Desa',
                            'Dusun' => 'Dusun',
                        ])
                        ->native(false)
                        ->live()
                        ->default('Desa')
                        ->afterStateUpdated(
                            fn (Select $component) => $component
                                ->getContainer()
                                ->getComponent('dynamicTypeFields')
                                ->getChildComponentContainer()
                                ->fill()
                        ),
                    Grid::make(2)
                        ->schema(fn (Get $get): array => match ($get('type')) {
                            'Dusun' => [
                                Repeater::make('Dusuns')
                                    ->label('Dusun')
                                    ->reorderable(false)
                                    ->schema([
                                        TextInput::make('Dusun')
                                            ->hiddenLabel()
                                            ->prefix('Dusun')
                                            ->placeholder('Masukkan Nama Dusun')
                                            ->live(onBlur: true),
                                        Repeater::make('RWS')
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->label('RW dan RT')
                                            ->helperText('Masukkan 001, 002, 010, dst.')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('RW')
                                                    ->hiddenLabel()
                                                    ->placeholder('Masukkan Nomor RW')
                                                    ->mask('999')
                                                    ->prefix('RW')
                                                    ->numeric()
                                                    ->minValue(1),
                                                Cluster::make([
                                                    TextInput::make('Mulai RT')
                                                        ->label('Mulai dari RT')
                                                        ->placeholder('Mulai dari RT')
                                                        ->prefix('RT')
                                                        ->mask('999')
                                                        ->minValue(1)
                                                        ->numeric(),
                                                    TextInput::make('Sampai RT')
                                                        ->placeholder('Sampai dengan RT')
                                                        ->minValue(1)
                                                        ->prefix('-')
                                                        ->mask('999')
                                                        ->numeric(),

                                                ])->hiddenLabel()

                                            ]),
                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => 'Dusun ' . $state['Dusun'] ?? null),
                            ],
                            'Desa' => [
                                Repeater::make('RWS')
                                    ->label('Jumlah RW dan RT')
                                    ->reorderable(false)
                                    ->helperText('Masukkan 001, 002, 010, dst.')
                                    ->schema([
                                        TextInput::make('RW')
                                            ->label('RW')
                                            ->hiddenLabel()
                                            ->placeholder('Masukkan Nomor RW')
                                            ->mask('999')
                                            ->prefix('RW')
                                            ->numeric()
                                            ->minValue(1)
                                            ->live(onBlur: true),

                                        Cluster::make([
                                            TextInput::make('Mulai RT')
                                                ->label('Mulai dari RT')
                                                ->placeholder('Mulai dari RT')
                                                ->prefix('RT')
                                                ->mask('999')
                                                ->minValue(1)
                                                ->numeric(),

                                            TextInput::make('Sampai RT')
                                                ->placeholder('Sampai dengan RT')
                                                ->minValue(1)
                                                ->prefix('-')
                                                ->mask('999')
                                                ->numeric(),

                                        ])->hiddenLabel(),
                                    ])->grid(3)->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => 'RW ' . str_pad($state['RW'], 2, '0', STR_PAD_LEFT) ?? null)

                            ],
                            default => [],
                        })
                        ->key('dynamicTypeFields'),

                ])

        ];
    }


    protected function processDusun(array $data): void
    {
        foreach ($data['Dusuns'] as $dataDusun) {
            $dusun = Dusun::firstOrCreate(
                [
                    'dusun_nama' => 'Dusun ' . $dataDusun['Dusun'],
                    'kel_id' => $data['kel_id'],
                ]

            );
            foreach ($dataDusun['RWS'] as $dataRw) {
                if (isset($dataRw['RW'])) {
                    $data = Arr::only($data, ['prov_id', 'kabkota_id', 'kec_id', 'kel_id']);
                    self::createData($data, $dataRw, $dusun);
                }
            }
        }
    }

    protected function processDesa(array $data): void
    {
        foreach ($data['RWS'] as $dataRw) {

            if (isset($dataRw['RW'])) {
                $data = Arr::only($data, ['prov_id', 'kabkota_id', 'kec_id', 'kel_id']);
                self::createData($data, $dataRw);
            }
        }
    }


    protected function createData(array $parentData, array $data, ?Dusun $dusun = null): Collection
    {
        return $this->createRW($parentData, $data)
            ->createRT($data)
            ->createWilayah($parentData, $dusun)
            ->createRTRWUser();
    }

    protected function createRW(array $parentData, array $data): self
    {
        $this->rwData = RW::firstOrCreate([
            'rw_id' => $data['RW'],
            'rw_nama' => 'RW ' . str_pad($data['RW'], 3, '0', STR_PAD_LEFT),
            'kel_id' => $parentData['kel_id'],
        ]);

        $this->rwAkun = User::firstOrCreate([
            'nik' => null,
            'name' => 'Operator ' . $this->rwData->rw_nama,
            'username' => 'RW' . str_pad($this->rwData->rw_id, 3, '0', STR_PAD_LEFT),
            'email' => null,
            'password' => Hash::make('kuripan'),
        ]);

        return $this;
    }

    protected function createRT(array $data): self
    {
        $rtMulai = (int) $data['Mulai RT'];
        $rtSampai = (int) $data['Sampai RT'];

        $rtData = collect(range($rtMulai, $rtSampai))->map(fn ($rtNumber) => [
            'rt_id' => $rtNumber,
            'rw_id' => $this->rwData->rw_id,
            'rt_nama' => 'RT ' . str_pad($rtNumber, 3, '0', STR_PAD_LEFT),
        ]);

        RT::upsert($rtData->except('wilayah_id')->toArray(), ['rt_id'], ['rt_nama', 'rw_id']);

        $this->rtAkun = $rtData->map(function ($rt) {
            return [
                'name' => $rt['rt_nama'],
                'nik' => null,
                'username' => 'RT' . str_pad($rt['rt_id'], 3, '0', STR_PAD_LEFT) . '_' . 'RW' . str_pad($rt['rw_id'], 3, '0', STR_PAD_LEFT),
                'email' => null,
                'password' => Hash::make('kuripan'),
                'wilayah_id' => $rt['rt_id'],
            ];
        });

        return $this;
    }

    protected function createWilayah(array $parentData, ?Dusun $dusun): self
    {
        $this->wilayahData = $this->rtAkun->map(function ($wilayah) use ($parentData, $dusun) {
            return [
                'wilayah_nama' => 'RT ' . str_pad($wilayah['wilayah_id'], 3, '0', STR_PAD_LEFT) . '/' . $this->rwData->rw_nama,
                'rw_id' => $this->rwData->rw_id,
                'rt_id' => $wilayah['wilayah_id'],
                'kel_id' => $parentData['kel_id'],
                'kec_id' => $parentData['kec_id'],
                'kabkota_id' => $parentData['kabkota_id'],
                'prov_id' => $parentData['prov_id'],
                'dusun_id' => $dusun->dusun_id ?? null,
            ];
        });

        Wilayah::upsert($this->wilayahData->toArray(), ['wilayah_id'], ['rw_id', 'kel_id', 'kec_id', 'kabkota_id', 'prov_id', 'wilayah_nama', 'dusun_id']);

        return $this;
    }

    protected function createRTRWUser(): Collection
    {
        User::upsert($this->rtAkun->map(fn ($item) => Arr::except($item, ['wilayah_id']))->toArray(), ['username'], ['name', 'email', 'password']);

        $rtUserIds = User::whereIn('username', $this->rtAkun->pluck('username'))->get();
        $wilayahIds = Wilayah::whereIn('wilayah_nama', $this->wilayahData->pluck('wilayah_nama'))->get('wilayah_id');

        DB::table('user_wilayahs')->insert($rtUserIds->map(function ($user, $key) use ($wilayahIds) {
            return [
                'user_id' => $user->id,
                'wilayah_id' => $wilayahIds[$key]->wilayah_id,
            ];
        })->toArray());

        $this->rwAkun->wilayah()->attach($wilayahIds);

        return $this->wilayahData;
    }



    // protected function createData(array $parentData, array $data, ?Dusun $dusun = null): Collection
    // {
    //     $wilayahData = collect();
    //     $rwAkun = collect();
    //     $rtAkun = collect();
    //     $rtMulai = (int) $data['Mulai RT'];
    //     $rtSampai = (int) $data['Sampai RT'];
    //     //buat data RW dan RT
    //     $rwData =  RW::firstOrCreate([
    //         'rw_id' => $data['RW'],
    //         'rw_nama' => 'RW ' . str_pad($data['RW'], 3, '0', STR_PAD_LEFT),
    //         'kel_id' => $parentData['kel_id'],
    //     ]);

    //     $rtData = collect(range($rtMulai, $rtSampai))->map(fn ($rtNumber) => [
    //         'rt_id' => $rtNumber,
    //         'rw_id' => $data['RW'],
    //         'rt_nama' => 'RT ' . str_pad($rtNumber, 3, '0', STR_PAD_LEFT),
    //     ]);


    //     RT::upsert($rtData->except(
    //         'wilayah_id'
    //     )->toArray(), ['rt_id'], ['rt_nama', 'rw_id']);

    //     $wilayahData = $rtData->map(function ($rt) use ($parentData, $rwData, $dusun) {
    //         return [
    //             'wilayah_nama' => 'RT ' . str_pad($rt['rt_id'], 3, '0', STR_PAD_LEFT) . '/' . $rwData->rw_nama,
    //             'rw_id' => $rwData->rw_id,
    //             'rt_id' => $rt['rt_id'],
    //             'kel_id' => $parentData['kel_id'],
    //             'kec_id' => $parentData['kec_id'],
    //             'kabkota_id' => $parentData['kabkota_id'],
    //             'prov_id' => $parentData['prov_id'],
    //             'dusun_id' => $dusun->dusun_id ?? null,
    //         ];
    //     });

    //     Wilayah::upsert($wilayahData->toArray(), ['wilayah_id'], ['rw_id', 'kel_id', 'kec_id', 'kabkota_id', 'prov_id', 'wilayah_nama', 'dusun_id']);

    //     $rwAkun = User::firstOrCreate([
    //         'nik' => null,
    //         'name' => 'Operator ' . $rwData->rw_nama,
    //         'username' => 'RW' . str_pad($rwData->rw_id, 3, '0', STR_PAD_LEFT),
    //         'email' => null,
    //         'password' => Hash::make('kuripan'),
    //     ]);

    //     $rtAkun = $wilayahData->map(function ($wilayah) {
    //         return [
    //             'name' => $wilayah['wilayah_nama'],
    //             'nik' => null,
    //             'username' => 'RT' . str_pad($wilayah['rt_id'], 3, '0', STR_PAD_LEFT) . '_' . 'RW' . str_pad($wilayah['rw_id'], 3, '0', STR_PAD_LEFT),
    //             'email' => null,
    //             'password' => Hash::make('kuripan'),
    //             'wilayah_id' => $wilayah['rt_id'],
    //         ];
    //     });

    //     User::upsert($rtAkun->map(function ($item) {
    //         return Arr::except($item, ['wilayah_id']);
    //     })->toArray(), ['username'], ['name', 'email', 'password']);

    //     $rtUserIds = User::whereIn('username', $rtAkun->pluck('username'))->get();
    //     $wilayahIds = Wilayah::whereIn('wilayah_nama', $wilayahData->pluck('wilayah_nama'))->get('wilayah_id');

    //     DB::table('user_wilayahs')->insert($rtUserIds->map(function ($user, $key) use ($wilayahIds) {
    //         return [
    //             'user_id' => $user->id,
    //             'wilayah_id' => $wilayahIds[$key]->wilayah_id,
    //         ];
    //     })->toArray());

    //     $rwUser = $rwAkun;
    //     $rwUser->wilayah()->attach($wilayahIds);


    //     return $wilayahData;
    // }


    protected function fillWilayah(): array
    {
        return
            [
                'prov_id' => '63',
                'kabkota_id' => '6371',
                'kec_id' => '637102',
                'kel_id' => '6371021001',
                'type' => 'Desa',
                'RWS' =>
                [
                    'RW' => '',
                    'Mulai RT' => '',
                    'Sampai RT' => '',
                ],
                [
                    'RW' => '',
                    'Mulai RT' => '',
                    'Sampai RT' => '',
                ]
            ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Semua', function (Builder $query) {
                $query->where('wilayah_id', '!=', null);
            })->label('Semua'),
        ];

        $wilayahData = Wilayah::orderBy('rw_id')->orderBy('rt_id')->get();

        foreach ($wilayahData as $wilayah) {
            $rwId = $wilayah->rw_id;

            if (!isset($tabs[$rwId])) {
                $tabs[$rwId] = Tab::make('RW ', $wilayah->rw->rw_nama)
                    ->modifyQueryUsing(function (Builder $query) use ($rwId) {
                        $query->where('rw_id', $rwId);
                    })->label($wilayah->rw->rw_nama);
            }
        }

        return $tabs;
    }
}
