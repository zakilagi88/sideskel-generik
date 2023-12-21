<?php

namespace App\Filament\Resources\WilayahResource\Pages;

use App\Filament\Resources\WilayahResource;
use App\Models\{Dusun, RT, RW, User, Wilayah, KabKota, Kecamatan, Kelurahan, Provinsi};
use Filament\Forms\Components\{Grid, Repeater, Select, TextInput};
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Forms\{Get, Set};
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;

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
                    self::processRWRT($data, $dataRw, $dusun);
                }
            }
        }
    }

    protected function processDesa(array $data): void
    {
        foreach ($data['RWS'] as $dataRw) {

            if (isset($dataRw['RW'])) {
                self::processRWRT($data, $dataRw);
            }
        }
    }

    protected function processRWRT(array $parentData, array $data, Dusun $dusun = null): void
    {
        $rw = RW::firstOrCreate(
            [
                'rw_nama' => 'RW ' . str_pad($data['RW'], 3, '0', STR_PAD_LEFT),
                'dusun_id' => $dusun->dusun_id ?? null,
                'kel_id' => $parentData['kel_id'],
            ]
        );


        $mulaiRT = (int) $data['Mulai RT'];
        $sampaiRT = (int) $data['Sampai RT'];

        for ($rtNumber = $mulaiRT; $rtNumber <= $sampaiRT; $rtNumber++) {
            $rt = RT::firstOrCreate(
                [
                    'rt_nama' => 'RT ' . str_pad($rtNumber, 3, '0', STR_PAD_LEFT),
                    'rw_id' => $rw->rw_id,
                ]
            );

            try {
                Wilayah::firstOrCreate(
                    [
                        'rw_id' => $rw->rw_id,
                        'rt_id' => $rt->rt_id,
                        'kel_id' => $parentData['kel_id'],
                        'kec_id' => $parentData['kec_id'],
                        'kabkota_id' => $parentData['kabkota_id'],
                        'prov_id' => $parentData['prov_id'],
                        'wilayah_nama' => $rt->rt_nama . '/' . $rw->rw_nama,
                        'dusun_id' => $dusun->dusun_id ?? null,
                    ]
                );
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

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
        $data = [];

        $wilayah_data = Wilayah::orderBy('rw_id')->orderBy('rt_id')->get();

        foreach ($wilayah_data as $wilayah) {
            $rw_id = $wilayah->rw_id;

            if (!isset($data[$rw_id])) {
                $data[$rw_id] = Tab::make('RW ', $wilayah->rws->rw_nama)
                    ->modifyQueryUsing(function (Builder $query) use ($rw_id) {
                        $query->where('rw_id', $rw_id);
                    })->label($wilayah->rws->rw_nama);
            }
        }

        return
            [
                'all' => Tab::make('Semua', function (Builder $query) {
                    $query->where('wilayah_id', '!=', null);
                })->label('Semua'),
            ]
            + $data;
    }
}
