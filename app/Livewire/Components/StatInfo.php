<?php

namespace App\Livewire\Components;

use App\Models\Penduduk;
use Filament\Widgets\Widget;

class StatInfo extends Widget
{

    protected static string $view = 'livewire.components.stat-info';

    public function getViewData(): array
    {
        return [
            'stats' => [
                [
                    'id' => 1,
                    'heading' => 'Penduduk',
                    'icon' => 'fas-users',
                    'value' => Penduduk::where('status_dasar', 'HIDUP')->count(),
                    'iconColor' => 'primary',
                ],
                [
                    'id' => 2,
                    'heading' => 'Kepala Keluarga',
                    'icon' => 'fas-person',
                    'value' => Penduduk::where(
                        [
                            'status_hubungan' => 'KEPALA KELUARGA',
                            'status_dasar' => 'HIDUP'
                        ]
                    )->count(),
                    'iconColor' => 'danger',
                ],

                [
                    'id' => 3,
                    'heading' => 'Penduduk Laki-laki',
                    'icon' => 'fas-person',
                    'value' => Penduduk::where(
                        [
                            'jenis_kelamin' => 'LAKI-LAKI',
                            'status_dasar' => 'HIDUP'
                        ]
                    )->count(),
                    'iconColor' => 'secondary',
                ],
                [
                    'id' => 4,
                    'heading' => 'Penduduk Perempuan',
                    'icon' => 'fas-person',
                    'value' => Penduduk::where(
                        [
                            'jenis_kelamin' => 'PEREMPUAN',
                            'status_dasar' => 'HIDUP'
                        ]
                    )->count(),
                    'iconColor' => 'info',
                ],

            ],
        ];
    }
}
