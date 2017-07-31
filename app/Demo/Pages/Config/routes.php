<?php

/** @var \Maduser\Minimal\Routers\Router $router */

$router->group([
    'middlewares' => [
        'App\\Demo\\Base\\Middlewares\\Cache' => [(5)],
    ]
], function() use ($router) {

    $router->get('pages/(:any)', [
        'controller' => \App\Demo\Pages\Controllers\PagesController::class,
        'action' => 'getStaticPage',
    ]);

    $router->get('pages/info', [
        'controller' => \App\Demo\Pages\Controllers\PagesController::class,
        'action' => 'info',
    ]);

    $router->get('pages/front', [
        'middlewares' => [
            'App\\Demo\\Base\\Middlewares\\StringReplacements' => [(5)],
            'App\\Demo\\Base\\Middlewares\\MakeView',
        ],
        'controller' => 'App\Demo\\Pages\Controllers\PagesController',
        'action' => 'frontController'
    ]);
});
