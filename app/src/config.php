<?php declare(strict_types=1);

const APP_ROOT = __DIR__;

return [
    'settings' => [
        'jwt' => [
            'secret' => '24985a4bcbc230e1b3389888b5427231',
            'issuer' => $_SERVER['SERVER_NAME'] ?? 'localhost',
            'expires_at' => 3600 //1 hour
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => 'db',
            'database' => 'lotr',
            'password' => 'root',
            'username' => 'root'
        ],
        'slim' => [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true,
        ],

        'cache' => [
            'filesystem_adapter' => [
                'namespace' => '',
                'default_life_time' => 3600,  //1 hour (time in seconds)
                'directory' => APP_ROOT.'/var/cache/dev'
            ]
        ]
    ]
];