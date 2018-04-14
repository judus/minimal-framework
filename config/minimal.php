<?php

return [

// ----------------------------------------------------------------------------
// PATHS

    'paths' => [
        'app' => 'app',
        'base' => '',
        'helpers' => 'helpers',
        'host' => 'localhost:8000',
        'modules' => 'app',
        'public' => 'public',
        'resources' => 'resources',
        'storage' => 'storage',
        'logs' => 'storage/logs',
        'system' => realpath(__DIR__ . '/../'),
        'translations' => 'storage/lang/lang.json',
        'views' => 'resources/views/my-theme'
    ],

// ----------------------------------------------------------------------------
// DATABASE

    'database' => [
        'default' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'user' => '',
            'password' => '',
            'database' => '',
            'charset' => 'utf8',
            'handler' => \PDO::class,
            'handlerOptions' => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]
        ]
    ],

// ----------------------------------------------------------------------------
// ERROR REPORTING

    'errors' => [
        'error_reporting' => 0,
        'display_errors' => 0
    ],

// ----------------------------------------------------------------------------
// LOGGING

    'log' => [
        'level' => 0,
        'benchmarks' => false
    ],

// ----------------------------------------------------------------------------
// STORAGE FOLDERS

    'storage' => [
        'app' => 'storage/app',
        'cache' => 'storage/cache',
        'logs' => 'storage/logs',
        'translation' => 'storage/translation'
    ],

];