<?php

/** @var \Maduser\Minimal\Routers\Router $route */

$route->group([
    'middlewares' => [
        'Acme\\Middlewares\\Cache' => [(5)],
    ]
], function() use ($route) {

    $route->get('pages/(:any)', [
        'controller' => \Acme\Pages\Controllers\PagesController::class,
        'action' => 'getStaticPage',
    ]);

    $route->get('pages/info', [
        'controller' => \Acme\Pages\Controllers\PagesController::class,
        'action' => 'info',
    ]);

    $route->get('pages/front', [
        'middlewares' => [
            'Acme\\Middlewares\\StringReplacements' => [(5)],
            'Acme\\Middlewares\\MakeView',
        ],
        'controller' => 'Acme\Pages\Controllers\PagesController',
        'action' => 'frontController'
    ]);
});
