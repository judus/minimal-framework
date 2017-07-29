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
        'user' => 'minimal',
        'password' => 'minimal',
        'database' => 'minimal',
        'handlerOptions' => [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    ],

// ----------------------------------------------------------------------------
// ERROR REPORTING

    'errors' => [
        'error_reporting' => E_ALL,
        'display_errors' => 1
    ],

];