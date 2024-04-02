<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages;

use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Widgets\WilayahOverview;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\{DesaKelurahanProfile, User, Wilayah};
use Filament\Actions;
use Filament\Forms\Components\{Grid, Group, Hidden, Placeholder, Repeater, TextInput, ToggleButtons};
use Filament\Forms\{Get, Set};
use Filament\Notifications\Notification;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Model;

class ListWilayahs extends ListRecords
{

    use ExposesTableToWidgets;

    protected $queuedRWData = [], $queuedRTData = [], $queuedWilayahData = [], $queuedUserRWData = [], $queuedUserRTData = [], $queuedUserData = [];

    public DesaKelurahanProfile $deskel;

    protected static string $resource = WilayahResource::class;

    public function mount(): void
    {
        $this->deskel = DesaKelurahanProfile::with('dk.kec.kabkota.prov', 'dk.kec.kabkota', 'dk.kec', 'dk')->first() ?? new DesaKelurahanProfile();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generate')
                ->modalWidth(MaxWidth::SixExtraLarge)
                ->label('Generate Wilayah')
                ->fillForm(self::fillWilayah())
                ->form(self::generateWilayahForm())
                ->action(
                    function (array $data) {
                        DB::beginTransaction();
                        try {
                            $this->processWilayah($data);

                            DB::commit();

                            self::notifyAdmin(
                                'Generate Wilayah Berhasil',
                                'Silahkan cek data wilayah di menu wilayah'
                            );
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            throw $th;
                        }
                    }
                )
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WilayahOverview::class,
        ];
    }

    private function notifyAdmin(string $title, string $body): void
    {
        $admin = User::role('Admin')->get('id');
        Notification::make()
            ->success()
            ->title($title)
            ->body($body)
            ->sendToDatabase($admin);
    }

    public function generateWilayahForm(): array
    {
        return   [
            Grid::make(2)
                ->schema([
                    Placeholder::make('wilayah')
                        ->hiddenLabel()
                        ->columns(1)
                        ->content(
                            function () {
                                $dk = $this->deskel;
                                $prov_nama = $dk->dk->kec->kabkota->prov->prov_nama ?? '';
                                $kabkota_nama = $dk->dk->kec->kabkota->kabkota_nama ?? '';
                                $kec_nama = $dk->dk->kec->kec_nama ?? '';
                                $deskel_nama = $dk->dk->deskel_nama ?? '';
                                $deskel_tipe = $dk->deskel_tipe ?? '';

                                return view('filament.pages.components.info-wilayah', compact('prov_nama', 'kabkota_nama', 'kec_nama', 'deskel_nama', 'deskel_tipe'));
                            }
                        ),
                    Hidden::make('deskel_id')
                        ->default($this->deskel->deskel_id),
                    Group::make()
                        ->key('struktur')
                        ->schema([
                            Placeholder::make('info')
                                ->hiddenLabel()
                                ->columns(1)
                                ->content(
                                    function () {
                                        return view('filament.pages.components.info-struktur-wilayah');
                                    }
                                ),
                            ToggleButtons::make('type')
                                ->inline()
                                ->options(
                                    [
                                        'Khusus' => 'Struktur Khusus',
                                        'Dasar' => 'Struktur Dasar',
                                        'Lengkap' => 'Struktur Lengkap',
                                    ]
                                )
                                ->label('Struktur Wilayah')
                                ->default('Dasar')
                                ->afterStateUpdated(
                                    fn (ToggleButtons $component) => ($component
                                        ->getContainer()->getParentComponent()->getContainer()->getComponent('strukturWilayah')->getChildComponentContainer()->fill())
                                )
                                ->live()
                                ->columns(1),
                        ]),
                    Grid::make(3)
                        ->schema(fn (Get $get): array => match ($get('type')) {
                            'Khusus' => [
                                TextInput::make('nama_1')
                                    ->label('Nama Satuan Wilayah Terbesar')
                                    ->placeholder('Contoh: Jorong, Banjar, dll')
                                    ->live(onBlur: true)
                                    ->required()
                                    ->helperText('Masukkan nama wilayah terbesar,contohnya Jorong, Banjar, dll')
                                    ->columnStart(['md' => 1, 'lg' => 1])
                                    ->columnSpan(2),

                                Repeater::make('tingkatan')
                                    ->label('Jumlah Satuan Wilayah')
                                    ->reorderable(false)
                                    ->required()
                                    ->helperText('Masukkan 001, 002, 010, dst.')
                                    ->schema([
                                        TextInput::make('tingkat_1')
                                            ->label('RW')
                                            ->hiddenLabel()
                                            ->placeholder(
                                                fn (Get $get): ?string => 'Masukkan Nama ' . $get('../../nama_1') ?? null
                                            )
                                            ->prefix(fn (Get $get): ?string => $get('../../nama_1') ?? null)
                                            ->live(onBlur: true),

                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn (array $state, Get $get): ?string => $get('nama_1') . '' . $state['tingkat_1'] ?? null)


                            ],
                            'Dasar' => [
                                TextInput::make('nama_1')
                                    ->label('Nama Wilayah Terbesar')
                                    ->placeholder('Contoh: RW')
                                    ->live(onBlur: true)
                                    ->required()

                                    ->helperText('Masukkan nama wilayah terbesar, contohnya RW')
                                    ->columnStart(['md' => 1, 'lg' => 1]),
                                TextInput::make('nama_2')
                                    ->label('Nama Wilayah')
                                    ->placeholder('Contoh: RT')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->helperText('Masukkan nama wilayah terkecil, contohnya RT')
                                    ->columnStart(['md' => 2, 'lg' => 2]),
                                Repeater::make('tingkatan')
                                    ->label('Jumlah Satuan Wilayah')
                                    ->reorderable(false)
                                    ->helperText('Masukkan 001, 002, 010, dst.')
                                    ->schema([
                                        TextInput::make('tingkat_1')
                                            ->label('RW')
                                            ->hiddenLabel()
                                            ->placeholder(
                                                fn (Get $get): ?string => 'Masukkan Nomor ' . $get('../../nama_1') ?? null
                                            )
                                            ->mask('999')
                                            ->prefix(fn (Get $get): ?string => $get('../../nama_1') ?? null)
                                            ->numeric()
                                            ->minValue(1)
                                            ->live(onBlur: true),

                                        Cluster::make([
                                            TextInput::make('Mulai')
                                                ->label('Mulai dari RT')
                                                ->placeholder('Mulai dari ')
                                                ->prefix(fn (Get $get): ?string => $get('../../nama_2') ?? null)
                                                ->mask('999')
                                                ->minValue(1)
                                                ->live(onBlur: true)
                                                ->numeric(),

                                            TextInput::make('Sampai')
                                                ->placeholder('Sampai dengan ')
                                                ->minValue(1)
                                                ->prefix('-')
                                                ->mask('999')
                                                ->numeric(),

                                        ])->hiddenLabel(),
                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn (array $state, Get $get): ?string => $get('nama_1') . ' ' . str_pad($state['tingkat_1'], 2, '0', STR_PAD_LEFT) ?? null)

                            ],
                            'Lengkap' => [
                                TextInput::make('nama_1')
                                    ->label('Nama Wilayah Terbesar')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: Dusun')
                                    ->helperText('Masukkan nama wilayah terbesar, contohnya Dusun')
                                    ->columnStart(['md' => 1, 'lg' => 1]),

                                TextInput::make('nama_2')
                                    ->label('Nama Wilayah Menengah')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: RW')
                                    ->helperText('Masukkan nama wilayah menengah, contohnya RW')
                                    ->columnStart(['md' => 2, 'lg' => 2]),

                                TextInput::make('nama_3')
                                    ->label('Nama Wilayah Terkecil')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: RT')
                                    ->helperText('Masukkan nama wilayah terkecil, contohnya RT')
                                    ->columnStart(['md' => 3, 'lg' => 3]),

                                Repeater::make('tingkatan')
                                    ->label('Dusun')
                                    ->hiddenLabel()
                                    ->reorderable(false)
                                    ->schema([
                                        TextInput::make('tingkat_1')
                                            ->hiddenLabel()
                                            ->prefix(fn (Get $get): ?string => $get('../../nama_1') ?? null)
                                            ->placeholder(fn (Get $get): ?string => 'Masukkan Nama ' . $get('../../nama_1') ?? null)
                                            ->live(onBlur: true),
                                        Repeater::make('sub_parent')
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->label(fn (Get $get): ?string => $get('../../nama_2') . 'dan' . $get('../../nama_3') ?? null)
                                            ->helperText('Masukkan 001, 002, 010, dst.')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('tingkat_2')
                                                    ->hiddenLabel()
                                                    ->placeholder(fn (Get $get): ?string => 'Masukkan Nomor ' . $get('../../../../nama_2') ?? null)
                                                    ->mask('999')
                                                    ->prefix(fn (Get $get): ?string => $get('../../../../nama_2') ?? null)
                                                    ->numeric()
                                                    ->reactive()
                                                    ->minValue(1),
                                                Cluster::make([
                                                    TextInput::make('Mulai')
                                                        ->label('Mulai dari ')
                                                        ->placeholder('Mulai dari ')
                                                        ->prefix(fn (Get $get): ?string => $get('../../../../nama_3') ?? null)
                                                        ->mask('999')
                                                        ->reactive()
                                                        ->minValue(1)
                                                        ->numeric(),
                                                    TextInput::make('Sampai')
                                                        ->placeholder('Sampai dengan ')
                                                        ->minValue(1)
                                                        ->prefix('-')
                                                        ->mask('999')
                                                        ->numeric(),

                                                ])->hiddenLabel()

                                            ]),
                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn (array $state, Get $get): ?string => $get('nama_1') . ' ' . str_pad($state['tingkat_1'], 2, '0', STR_PAD_LEFT) ?? null)
                            ],

                            default => [],
                        })
                        ->key('strukturWilayah'),

                ])

        ];
    }

    public function insertParentWilayah(array $data): Model
    {
        return Wilayah::firstOrCreate($data);
    }

    public function insertChildrenWilayah(array $data): void
    {
        Wilayah::insert($data);
    }

    public function insertUsers(array $users): void
    {
        User::insert($users);
    }

    public function insertRoles(array $roles): void
    {
        DB::table('model_has_roles')->insert($roles);
    }

    public function generateWilayahName(int $tingkat, ...$args): string
    {
        switch ($tingkat) {
            case 1:
                return $args[0] . ' ' . $args[1];
            case 2:
                return $args[0] . ' ' . str_pad($args[1], 3, '0', STR_PAD_LEFT) . ' / ' . $args[2] . ' ' . str_pad($args[3], 3, '0', STR_PAD_LEFT);
            case 3:
                return $args[0] . ' ' . str_pad($args[1], 3, '0', STR_PAD_LEFT) . ' / ' . $args[2] . str_pad($args[3], 3, '0', STR_PAD_LEFT) . ' / ' . $args[4] . ' ' . $args[5];
            default:
                return '';
        }
    }

    public function generateUsername(int $tingkat, ...$args): string
    {
        switch ($tingkat) {
            case 1:
                return $args[0] . str_pad($args[1], 3, '0', STR_PAD_LEFT);
            case 2:
                return $args[0] . str_pad($args[1], 3, '0', STR_PAD_LEFT) . '_' . $args[2] .  str_pad($args[3], 3, '0', STR_PAD_LEFT);
            case 3:
                return $args[0] . str_pad($args[1], 3, '0', STR_PAD_LEFT) . '_' . $args[2] . str_pad($args[3], 3, '0', STR_PAD_LEFT) . '_' . $args[4] . $args[5];
            default:
                return '';
        }
    }

    private function generateRoles(array $userIds, int $roleId): array
    {
        $roles = [];
        foreach ($userIds as $userId) {
            $roles[] = [
                'role_id' => $roleId,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ];
        }
        return $roles;
    }

    public function processWilayah(array $data): void
    {
        $parentUser = [];
        $subparent = [];
        $childrenUser = [];

        foreach ($data['tingkatan'] as $dataTingkatan) {
            if (isset($dataTingkatan['tingkat_1'])) {
                $dataTingkatan['tingkat_1'] = $dataTingkatan['tingkat_1'];

                $parents = [
                    'wilayah_nama' => $this->generateWilayahName(1, $data['nama_1'], $dataTingkatan['tingkat_1']),
                    'deskel_id' => $data['deskel_id'],
                    'parent_id' => null,
                ];
                $parent = $this->insertParentWilayah($parents);
                $parentUser[] = [
                    'name' => $this->generateWilayahName(1, $data['nama_1'], $dataTingkatan['tingkat_1']),
                    'username' => $this->generateUsername(1, $data['nama_1'], $dataTingkatan['tingkat_1']),
                    'password' => Hash::make(strtolower($this->deskel->dk->deskel_nama)),
                    'wilayah_id' => $parent->wilayah_id,
                ];
                $wilayah_id_counter = 1;
                if ($data['type'] == 'Dasar') {
                    $children = [];
                    foreach (range($dataTingkatan['Mulai'], $dataTingkatan['Sampai']) as $childNumber) {
                        $children[] = [
                            'wilayah_id' => $parent->wilayah_id + $wilayah_id_counter,
                            'wilayah_nama' => $this->generateWilayahName(2, $data['nama_2'], $childNumber, $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'deskel_id' => $data['deskel_id'],
                            'parent_id' => $parent->wilayah_id,
                        ];
                        $childrenUser[] = [
                            'name' => $this->generateWilayahName(2, $data['nama_2'], $childNumber, $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'username' => $this->generateUsername(2, $data['nama_2'], $childNumber, $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'password' => Hash::make(strtolower($this->deskel->dk->deskel_nama)),
                            'wilayah_id' => $parent->wilayah_id + $wilayah_id_counter,
                        ];
                        $wilayah_id_counter++;
                    }
                    $this->insertChildrenWilayah($children);
                }
                if (($data['type'] == 'Lengkap' && isset($dataTingkatan['sub_parent']))) {
                    foreach ($dataTingkatan['sub_parent'] as $subParent) {
                        $sub_parent = [
                            'wilayah_nama' => $this->generateWilayahName(2, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'deskel_id' => $data['deskel_id'],
                            'parent_id' => $parent->wilayah_id,
                        ];
                        $subparent = $this->insertParentWilayah($sub_parent);
                        $subparentUser[] = [
                            'name' => $this->generateWilayahName(2, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'username' => $this->generateUsername(2, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'password' => Hash::make(strtolower($this->deskel->dk->deskel_nama)),
                            'wilayah_id' => $parent->wilayah_id + $wilayah_id_counter,
                        ];
                        $children = [];
                        foreach (range($subParent['Mulai'], $subParent['Sampai']) as $childNumber) {
                            $children[] = [
                                'wilayah_id' => $subparent->wilayah_id + $wilayah_id_counter,
                                'wilayah_nama' => $this->generateWilayahName(3, $data['nama_3'], $childNumber, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                                'deskel_id' => $data['deskel_id'],
                                'parent_id' => $subparent->wilayah_id,
                            ];
                            $childrenUser[] = [
                                'name' => $this->generateWilayahName(3, $data['nama_3'], $childNumber, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                                'username' => $this->generateUsername(3, $data['nama_3'], $childNumber, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                                'password' => Hash::make(strtolower($this->deskel->dk->deskel_nama)),
                                'wilayah_id' => $subparent->wilayah_id + $wilayah_id_counter,
                            ];
                            $wilayah_id_counter++;
                        }
                        $this->insertChildrenWilayah($children);
                    }
                }
            }
        }
        $this->updateDeskeltipe($data['type']);
        switch ($data['type']) {
            case 'Khusus':
                $this->insertUsers($parentUser);
                $this->insertRoles($this->generateRoles($this->getUserIds($parentUser), 3));
                break;
            case 'Dasar':
                $this->insertUsers(array_merge($parentUser, $childrenUser));
                $this->insertRoles(array_merge($this->generateRoles($this->getUserIds($parentUser), 4), $this->generateRoles($this->getUserIds($childrenUser), 3)));
                break;
            case 'Lengkap':
                $this->insertUsers(array_merge($parentUser, $childrenUser, $subparentUser));
                $this->insertRoles(array_merge($this->generateRoles($this->getUserIds(array_merge($parentUser, $subparentUser)), 4), $this->generateRoles($this->getUserIds($childrenUser), 3)));
                break;
            default:
                break;
        }
    }

    private function updateDeskeltipe($tipe)
    {
        $this->deskel->update(['deskel_tipe' => $tipe]);
    }

    private function getUserIds($userRecords)
    {
        return User::whereIn('username', array_column($userRecords, 'username'))->pluck('id')->toArray();
    }

    protected function fillWilayah(): array
    {
        $dk = $this->deskel;
        return
            [
                'deskel_id' => $dk->deskel_id,
                'type' => 'Dasar',
                'tingkatan' =>
                [
                    'tingkat_1' => '',
                    'Mulai' => '',
                    'Sampai' => '',
                ],
                [
                    'tingkat_2' => '',
                    'Mulai' => '',
                    'Sampai' => '',
                ],

            ];
    }
}
