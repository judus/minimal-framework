<?php

return [
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
        'path' => realpath(__DIR__ .'/../')
    ]
];