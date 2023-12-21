<?php

namespace App\Filament\Pages;

use App\Filament\Resources\WilayahResource;
use App\Models\{KabKota, Kecamatan, Kelurahan, Provinsi};
use Filament\Forms\{Form, Get, Set};
use Filament\Forms\Components\{FileUpload, Grid, Repeater, Select, TextInput, Toggle};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page as PagesPage;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Str;

class Generator extends PagesPage implements HasForms
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Generator Wilayah';

    protected static string $view = 'filament.pages.components.generator';


    public array $data = [];

    public $nilaiRW = 1;


    public function mount()
    {

        $this->form->fill(
            [
                // 'JRT' => [
                //     [
                //         'RW' => sprintf('%03d', count($this->form->getState()['JRT'] ?? 0) + 1),
                //     ],
                //     [
                //         'RW' => sprintf('%03d', count($this->form->getState()['JRT'] ?? 0) + 2),
                //     ],

                // ]
            ]
        );
        // dd($this->form->getState());
    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('prov_id')
                    ->label('Provinsi')
                    ->options(
                        Provinsi::pluck('prov_nama', 'prov_id')
                    )
                    ->live()
                    ->dehydrated(),
                Select::make('kabkota_id')
                    ->label('Kab/Kota')
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
                    ->options(
                        fn (Get $get): Collection => Kelurahan::query()
                            ->where('kec_id', $get('kec_id'))
                            ->pluck('kel_nama', 'kel_id')
                    )
                    ->live()
                    ->dehydrated(),

                Select::make('type')
                    ->options([
                        'Desa' => 'Desa',
                        'Dusun' => 'Dusun',
                    ])
                    ->native(false)
                    ->default('Desa')
                    ->live()
                    ->afterStateUpdated(fn (Select $component) => $component
                        ->getContainer()
                        ->getComponent('dynamicTypeFields')
                        ->getChildComponentContainer()
                        ->fill()),

                Grid::make(2)
                    ->schema(fn (Get $get): array => match ($get('type')) {
                        'Desa' => [
                            Repeater::make('JRT')
                                ->label('Jumlah RW dan RT')
                                ->schema([
                                    TextInput::make('RW')
                                        ->label('RW')
                                        ->placeholder('Masukkan Nomor RW')
                                        ->mask('999')
                                        ->prefix('RW')
                                        ->hint('Gunakan tiga digit angka untuk RW. Contoh: 001, 002, 010, dst.')
                                        ->numeric()
                                        // ->default(
                                        //     function (Set $set) {
                                        //         $rwIndex = count($this->form->getState()['JRT'] ?? 0) + 1;
                                        //         return sprintf('%03d', $rwIndex - 1);
                                        //     }
                                        // )
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

                                    ])->label('RT')->hint('Gunakan tiga digit angka untuk RT. Contoh: 001, 002, 010, dst.'),
                                ])->grid(2)->columnSpanFull()
                                ->itemLabel(fn (array $state): ?string => 'RW ' . str_pad($state['RW'], 2, '0', STR_PAD_LEFT) ?? null),
                        ],
                        'Dusun' => [
                            Repeater::make('coba')
                                ->label('Jumlah Dusun')
                                ->schema(
                                    [
                                        TextInput::make('Dusun')
                                            ->label('Nama Dusun')
                                            ->placeholder('Masukkan Nama Dusun')
                                            ->hint('Masukkan nama dusun jika ada.')
                                            ->reactive()
                                            // ->visible(
                                            //     function (Get $get): bool {

                                            //         if ($get('is_dusun') == true) {
                                            //             return true;
                                            //         }

                                            //         return false;
                                            //     }
                                            // )
                                            ->autofocus(),
                                    ]
                                )

                        ],
                        default => [],
                    })
                    ->key('dynamicTypeFields'),

                // Repeater::make('JRT')
                //     ->label('Jumlah RW dan RT')
                //     ->key('JRT')
                //     ->defaultItems(2)
                //     ->schema([
                //         TextInput::make('Dusun')
                //             ->label('Nama Dusun')
                //             ->placeholder('Masukkan Nama Dusun')
                //             ->hint('Masukkan nama dusun jika ada.')
                //             ->reactive()
                //             ->visible(
                //                 function (Get $get): bool {

                //                     if ($get('is_dusun') == true) {
                //                         return true;
                //                     }

                //                     return false;
                //                 }
                //             )
                //             ->autofocus(),
                //         TextInput::make('RW')
                //             ->label('RW')
                //             ->placeholder('Masukkan Nomor RW')
                //             ->mask('999')
                //             ->prefix('RW')
                //             ->hint('Gunakan tiga digit angka untuk RW. Contoh: 001, 002, 010, dst.')
                //             ->numeric()
                //             ->default(
                //                 function (Set $set) {
                //                     $rwIndex = count($this->form->getState()['JRT'] ?? 0) + 1;
                //                     return sprintf('%03d', $rwIndex - 1);
                //                 }
                //             )
                //             ->minValue(1)
                //             ->live(onBlur: true),
                //         Cluster::make([
                //             TextInput::make('Mulai RT')
                //                 ->label('Mulai dari RT')
                //                 ->placeholder('Mulai dari RT')
                //                 ->prefix('RT')
                //                 ->mask('999')
                //                 ->minValue(1)
                //                 ->numeric(),

                //             TextInput::make('Sampai RT')
                //                 ->placeholder('Sampai dengan RT')
                //                 ->minValue(1)
                //                 ->prefix('-')
                //                 ->mask('999')
                //                 ->numeric(),

                //         ])->label('RT')->hint('Gunakan tiga digit angka untuk RT. Contoh: 001, 002, 010, dst.'),
                //     ])->grid(2)->columnSpanFull()
                //     ->itemLabel(fn (array $state): ?string => 'RW ' . str_pad($state['RW'], 2, '0', STR_PAD_LEFT) ?? null)


            ])->columns(2)
            ->statePath('data');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }
}