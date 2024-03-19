<?php

return [

    'resources' => [
        'AutenticationLogResource' => \App\Filament\Resources\Shield\AutentikasiLogResource::class,
    ],

    'authenticable-resources' => [
        \App\Models\User::class,
    ],

    'navigation' => [
        'authentication-log' => [
            'register' => true,
            'sort' => 1,
            'icon' => 'fas-chart-simple',
        ],
    ],

    'sort' => [
        'column' => 'login_at',
        'direction' => 'desc',
    ],
];
