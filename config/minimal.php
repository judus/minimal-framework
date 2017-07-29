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
        'system' => realpath(__DIR__ . '/../')
    ],

// ----------------------------------------------------------------------------
// APPLICATION

    'app' => [
        'bindingsFile' => 'config/bindings.php',
        'configFile' => 'config/config.php',
        'providersFile' => 'config/providers.php',
        'routesFile' => 'config/routes.php',
    ],

// ----------------------------------------------------------------------------
// MODULES

    'modules' => [
        'bindingsFile' => 'Config/bindings.php',
        'configFile' => 'Config/config.php',
        'providersFile' => 'Config/providers.php',
        'routesFile' => 'Config/routes.php',
    ],

// ----------------------------------------------------------------------------
// DATABASE

    'database' => [
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
    ],

// ----------------------------------------------------------------------------
// ERROR REPORTING

    'errors' => [
        'error_reporting' => 0,
        'display_errors' => 0
    ],

// ----------------------------------------------------------------------------
// STORAGE FOLDERS

    'storage' => [
        'app' => 'storage/app',
        'cache' => 'storage/cache',
        'logs' => 'storage/logs',
        'translation' => 'storage/translation',
        'content' => 'storage/content'
    ],

];