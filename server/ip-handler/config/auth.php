<?php

use Zaber04\LumenApiResources\Models\User;

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class
        ]
    ],

    'jwt_refresh_minutes' => env('JWT_REFRESH_MINUTES', 60),
    'jwt_refresh_ttl' => env('JWT_REFRESH_TTL', 60),
];
