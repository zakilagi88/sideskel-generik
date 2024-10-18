<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages;

use App\Exports\UserWilayahExport;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Widgets\WilayahOverview;
use App\Filament\Pages\Dashboard;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Wilayah};
use App\Models\Deskel\DesaKelurahanProfile;
use App\Settings\GeneralSettings;
use Filament\Actions;
use Filament\Forms\Components\{Grid, Group, Hidden, Placeholder, Repeater, TextInput, ToggleButtons};
use Filament\Forms\{Get, Set};
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ListWilayahs extends ListRecords
{

    use ExposesTableToWidgets;

    protected $queuedRWData = [], $queuedRTData = [], $queuedWilayahData = [], $queuedUserRWData = [], $queuedUserRTData = [], $queuedUserData = [];

    public $exports = [];

    public DesaKelurahanProfile $deskel;

    protected static string $resource = WilayahResource::class;

    public function mount(): void
    {
        $this->deskel = DesaKelurahanProfile::with('dk', 'prov', 'kec', 'kabkota')->first() ?? new DesaKelurahanProfile();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
            Actions\CreateAction::make()->label('Tambah Data'),
            Actions\Action::make('generate')
                ->modalWidth(MaxWidth::SixExtraLarge)
                ->label('Generate Wilayah')
                ->fillForm(self::fillWilayah())
                ->form(self::generateWilayahForm())
                ->action(
                    function (array $data) {
                        DB::beginTransaction();
                        try {

                            app(GeneralSettings::class)->fill(['sebutan_wilayah' => [$data['type'] => $this->getTypeWilayah($data)]]);

                            $userWilayah =  $this->processWilayah($data);

                            $filePath = 'private/deskel/exports/akun_pengguna.xlsx';

                            // Store the file using the storage facade
                            Storage::disk('local')->put($filePath, '');

                            // Export the Excel file, overwriting if necessary
                            Excel::store(new UserWilayahExport($userWilayah), $filePath, 'local', \Maatwebsite\Excel\Excel::XLSX);


                            DB::commit();

                            $this->redirect(Dashboard::getUrl());

                            self::notifyAdmin(
                                'Generate Wilayah Berhasil',
                                'Silahkan cek data wilayah di Menu Wilayah',
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

    protected function getTypeWilayah(array $data): array
    {
        switch ($data['type']) {
            case 'Khusus':
                return [$data['nama_1']];
                break;
            case 'Dasar':
                return [$data['nama_1'], $data['nama_2']];
                break;
            case 'Lengkap':
                return [$data['nama_1'], $data['nama_2'], $data['nama_3']];
                break;
            default:
                return '';
                break;
        }
    }

    public function notifyAdmin(string $title, string $body): void
    {
        $admin = User::role('Admin')->get('id');
        Notification::make()
            ->success()
            ->title($title)
            ->body($body)
            ->actions([
                Action::make('Download Akun Pengguna')
                    ->color('primary')
                    ->button()
                    ->url(route('filament.panel.downloads'), shouldOpenInNewTab: true)
            ])
            ->sendToDatabase($admin)
            ->send();
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
                                $prov_nama = $dk->prov?->prov_nama ?? '';
                                $kabkota_nama = $dk->kabkota?->kabkota_nama ?? '';
                                $kec_nama = $dk->kec?->kec_nama ?? '';
                                $deskel_nama = $dk->dk?->deskel_nama ?? '';

                                return view('filament.pages.components.info-wilayah', compact('prov_nama', 'kabkota_nama', 'kec_nama', 'deskel_nama'));
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
                                    fn(ToggleButtons $component) => ($component
                                        ->getContainer()->getParentComponent()->getContainer()->getComponent('strukturWilayah')->getChildComponentContainer()->fill())
                                )
                                ->live()
                                ->columns(1),
                        ]),
                    Grid::make(3)
                        ->schema(fn(Get $get): array => match ($get('type')) {
                            'Khusus' => [
                                TextInput::make('nama_1')
                                    ->label('Nama Satuan Wilayah Terbesar')
                                    ->placeholder('Contoh: Jorong, Banjar, dll')
                                    ->default('Jorong')
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
                                                fn(Get $get): ?string => 'Masukkan Nama ' . $get('../../nama_1') ?? null
                                            )
                                            ->prefix(fn(Get $get): ?string => $get('../../nama_1') ?? null)
                                            ->live(onBlur: true),

                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn(array $state, Get $get): ?string => $get('nama_1') . '' . $state['tingkat_1'] ?? null)


                            ],
                            'Dasar' => [
                                TextInput::make('nama_1')
                                    ->label('Nama Wilayah Terbesar')
                                    ->placeholder('Contoh: RW')
                                    ->default('RW')
                                    ->live(onBlur: true)
                                    ->required()
                                    ->helperText('Masukkan nama wilayah terbesar, contohnya RW')
                                    ->columnStart(['md' => 1, 'lg' => 1]),
                                TextInput::make('nama_2')
                                    ->label('Nama Wilayah')
                                    ->placeholder('Contoh: RT')
                                    ->default('RT')
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
                                            ->placeholder(fn(Get $get): ?string => 'Masukkan Nomor ' . $get('../../nama_1') ?? null)
                                            ->mask('999')
                                            ->prefix(fn(Get $get): ?string => $get('../../nama_1') ?? null)
                                            ->numeric()
                                            ->minValue(1)
                                            ->live(onBlur: true),

                                        Group::make([
                                            TextInput::make('Mulai')
                                                ->label('Mulai dari RT')
                                                ->hiddenLabel()
                                                ->placeholder('Mulai dari ')
                                                ->prefix(fn(Get $get): ?string => $get('../../nama_2') ?? null)
                                                ->mask('999')
                                                ->minValue(1)
                                                ->live(onBlur: true)
                                                ->numeric(),
                                            TextInput::make('Sampai')
                                                ->label('Sampai dengan RT')
                                                ->hiddenLabel()
                                                ->placeholder('Sampai dengan ')
                                                ->minValue(1)
                                                ->mask('999')
                                                ->numeric(),

                                        ])->extraAttributes([
                                            'class' => 'gap-10',
                                        ])->columns(2)->hiddenLabel(),
                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn(array $state, Get $get): ?string => $get('nama_1') . ' ' . str_pad($state['tingkat_1'], 2, '0', STR_PAD_LEFT) ?? null)

                            ],
                            'Lengkap' => [
                                TextInput::make('nama_1')
                                    ->label('Nama Wilayah Terbesar')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: Dusun')
                                    ->default('Dusun')
                                    ->helperText('Masukkan nama wilayah terbesar, contohnya Dusun')
                                    ->columnStart(['md' => 1, 'lg' => 1]),

                                TextInput::make('nama_2')
                                    ->label('Nama Wilayah Menengah')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: RW')
                                    ->default('RW')
                                    ->helperText('Masukkan nama wilayah menengah, contohnya RW')
                                    ->columnStart(['md' => 2, 'lg' => 2]),

                                TextInput::make('nama_3')
                                    ->label('Nama Wilayah Terkecil')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder('Contoh: RT')
                                    ->default('RT')
                                    ->helperText('Masukkan nama wilayah terkecil, contohnya RT')
                                    ->columnStart(['md' => 3, 'lg' => 3]),

                                Repeater::make('tingkatan')
                                    ->label('Dusun')
                                    ->hiddenLabel()
                                    ->reorderable(false)
                                    ->schema([
                                        TextInput::make('tingkat_1')
                                            ->hiddenLabel()
                                            ->prefix(fn(Get $get): ?string => $get('../../nama_1') ?? null)
                                            ->placeholder(fn(Get $get): ?string => 'Masukkan Nama ' . $get('../../nama_1') ?? null)
                                            ->live(onBlur: true),
                                        Repeater::make('sub_parent')
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->label(fn(Get $get): ?string => $get('../../nama_2') . 'dan' . $get('../../nama_3') ?? null)
                                            ->helperText('Masukkan 001, 002, 010, dst.')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('tingkat_2')
                                                    ->hiddenLabel()
                                                    ->placeholder(fn(Get $get): ?string => 'Masukkan Nomor ' . $get('../../../../nama_2') ?? null)
                                                    ->mask('999')
                                                    ->prefix(fn(Get $get): ?string => $get('../../../../nama_2') ?? null)
                                                    ->numeric()
                                                    ->reactive()
                                                    ->minValue(1),
                                                Group::make([
                                                    TextInput::make('Mulai')
                                                        ->label('Mulai dari ')
                                                        ->placeholder('Mulai dari ')
                                                        ->prefix(fn(Get $get): ?string => $get('../../../../nama_3') ?? null)
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
                                                ])->columns(2)->hiddenLabel()

                                            ]),
                                    ])->grid(2)->columnSpanFull()
                                    ->itemLabel(fn(array $state, Get $get): ?string => $get('nama_1') . ' ' . str_pad($state['tingkat_1'], 2, '0', STR_PAD_LEFT) ?? null)
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

    public function processWilayah(array $data): Collection
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
                    'tingkatan' => 1,
                ];
                $parent = $this->insertParentWilayah($parents);
                $pass = Str::random(4) . rand(10, 99);
                $parentUser[] = [
                    'name' => $this->generateWilayahName(1, $data['nama_1'], $dataTingkatan['tingkat_1']),
                    'username' => $this->generateUsername(1, $data['nama_1'], $dataTingkatan['tingkat_1']),
                    'plain_password' => $pass,
                    'password' => Hash::make($pass),
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
                            'tingkatan' => 2,
                        ];
                        $pass = Str::random(4) . rand(10, 99);
                        $childrenUser[] = [
                            'name' => $this->generateWilayahName(2, $data['nama_2'], $childNumber, $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'username' => $this->generateUsername(2, $data['nama_2'], $childNumber, $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'plain_password' => $pass,
                            'password' => Hash::make($pass),
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
                            'tingkatan' => 2,

                        ];
                        $subparent = $this->insertParentWilayah($sub_parent);
                        $pass = Str::random(4) . rand(10, 99);
                        $subparentUser[] = [
                            'name' => $this->generateWilayahName(2, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'username' => $this->generateUsername(2, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                            'plain_password' => $pass,
                            'password' => Hash::make($pass),
                            'wilayah_id' => $parent->wilayah_id + $wilayah_id_counter,
                        ];
                        $children = [];
                        foreach (range($subParent['Mulai'], $subParent['Sampai']) as $childNumber) {
                            $children[] = [
                                'wilayah_id' => $subparent->wilayah_id + $wilayah_id_counter,
                                'wilayah_nama' => $this->generateWilayahName(3, $data['nama_3'], $childNumber, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                                'deskel_id' => $data['deskel_id'],
                                'parent_id' => $subparent->wilayah_id,
                                'tingkatan' => 3,

                            ];
                            $pass = Str::random(4) . rand(10, 99);
                            $childrenUser[] = [
                                'name' => $this->generateWilayahName(3, $data['nama_3'], $childNumber, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                                'username' => $this->generateUsername(3, $data['nama_3'], $childNumber, $data['nama_2'], $subParent['tingkat_2'], $data['nama_1'], $dataTingkatan['tingkat_1']),
                                'plain_password' => $pass,
                                'password' => Hash::make($pass),
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
                $users = collect(array_merge($parentUser));
                // Collection of users with password
                $usersPartitioned = $users->map(function ($user) {
                    return [
                        'with_hash' => collect($user)->except(['plain_password'])->toArray(),
                        'without_hash' => collect($user)->except(['password', 'wilayah_id'])->toArray(),
                    ];
                });

                $usersWithHash = $usersPartitioned->pluck('with_hash');
                $usersWithoutHash = $usersPartitioned->pluck('without_hash');

                $this->insertUsers($usersWithHash->toArray());
                $this->insertRoles($this->generateRoles($this->getUserIds($parentUser), 3));

                return $usersWithoutHash;
                break;
            case 'Dasar':
                $users = collect(array_merge($parentUser, $childrenUser));

                $usersPartitioned = $users->map(function ($user) {
                    return [
                        'with_hash' => collect($user)->except(['plain_password'])->toArray(),
                        'without_hash' => collect($user)->except(['password', 'wilayah_id'])->toArray(),
                    ];
                });

                $usersWithHash = $usersPartitioned->pluck('with_hash');
                $usersWithoutHash = $usersPartitioned->pluck('without_hash');

                $this->insertUsers($usersWithHash->toArray());
                $this->insertRoles(array_merge($this->generateRoles($this->getUserIds($parentUser), 3), $this->generateRoles($this->getUserIds($childrenUser), 4)));

                return $usersWithoutHash;

                break;
            case 'Lengkap':
                $users = collect(array_merge($parentUser, $childrenUser, $subparentUser));

                $usersPartitioned = $users->map(function ($user) {
                    return [
                        'with_hash' => collect($user)->except(['plain_password'])->toArray(),
                        'without_hash' => collect($user)->except(['password', 'wilayah_id'])->toArray(),
                    ];
                });

                $usersWithHash = $usersPartitioned->pluck('with_hash');
                $usersWithoutHash = $usersPartitioned->pluck('without_hash');

                $this->insertUsers($usersWithHash->toArray());
                $this->insertRoles(array_merge($this->generateRoles($this->getUserIds(array_merge($parentUser, $subparentUser)), 3), $this->generateRoles($this->getUserIds($childrenUser), 4)));

                return $usersWithoutHash;
                break;
            default:
                break;
        }
    }



    private function updateDeskeltipe($tipe)
    {
        $this->deskel->update(['struktur' => $tipe]);
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
                'nama_1' => 'RW',
                'nama_2' => 'RT',
                'tingkatan' => [
                    [
                        'tingkat_1' => '001',
                        'Mulai' => '001',
                        'Sampai' => '010',
                    ],
                    [
                        'tingkat_1' => '002',
                        'Mulai' => '001',
                        'Sampai' => '010',
                    ]
                ]
            ];
    }
}
