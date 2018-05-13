<?php

use Maduser\Minimal\Framework\Facades\Router;

Router::group([
    'middlewares' => [
        'App\\Demo\\Base\\Middlewares\\Cache' => [(5)],
    ]
], function() {

    Router::get('pages/(:any)', [
        'controller' => \App\Demo\Pages\Controllers\PagesController::class,
        'action' => 'getStaticPage',
    ]);

    Router::get('pages/info', [
        'controller' => \App\Demo\Pages\Controllers\PagesController::class,
        'action' => 'info',
    ]);

    Router::get('pages/front', [
        'middlewares' => [
            'App\\Demo\\Base\\Middlewares\\StringReplacements' => [(5)],
            'App\\Demo\\Base\\Middlewares\\MakeView',
        ],
        'controller' => 'App\Demo\\Pages\Controllers\PagesController',
        'action' => 'frontController'
    ]);
});
