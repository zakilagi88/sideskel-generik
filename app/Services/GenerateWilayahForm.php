<?php

namespace App\Services;

use App\Models\{KabKota, Kecamatan, Kelurahan, Provinsi};

use Filament\Forms\{Get, Set};
use Filament\Forms\Components\{Grid, Repeater, Select, TextInput};
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

final class GenerateWilayahForm
{
    public static function schema(): array
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
}