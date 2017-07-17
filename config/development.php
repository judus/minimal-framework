<?php

return [

// ----------------------------------------------------------------------------
// PATHS

    'paths' => [
        'host' => '',
        'base' => '',
    ],

// ----------------------------------------------------------------------------
// DATABASE

    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'testuser',
        'password' => '494949',
        'database' => 'my_gally_test',
        'charset' => 'utf8',
        'handler' => \PDO::class,
        'handlerOptions' => [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    ],

// ----------------------------------------------------------------------------
// ERROR REPORTING

    'errors' => [
        'error_reporting' => E_ALL,
        'display_errors' => 1
    ],

// ----------------------------------------------------------------------------
// CACHE
/*
    'cache' => [
        'path' => 'app/storage/cache',
    ],
*/
// ----------------------------------------------------------------------------
// SYSTEM
/*
    'system' => [
        'app' => 'app',
        'modules' => 'app/modules'
    ],
*/
    'app' => [
        'path' => 'app',
        'configFile' => 'config/config.php',
        'bindingsFile' => 'config/bindings.php',
        'providersFile' => 'config/providers.php',
        'routesFile' => 'config/routes.php',
    ],

    'modules' => [
        'path' => 'app',
        'configFile' => 'Config/config.php',
        'bindingsFile' => 'Config/bindings.php',
        'providersFile' => 'Config/providers.php',
        'routesFile' => 'Config/routes.php',
    ],

    'cache' => [
        'path' => 'storage/cache'
    ],

    'content' => [
        'path' => 'storage/content'
    ],

    'system' => [
        'path' => realpath(__DIR__ . '/../')
    ]

];