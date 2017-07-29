<?php

/** @var \Maduser\Minimal\Routers\Router $route */

$route->group([
    'middlewares' => [
        'Acme\\Demo\\Base\\Middlewares\\Cache' => [(5)],
    ]
], function() use ($route) {

    $route->get('pages/(:any)', [
        'controller' => \Acme\Demo\Pages\Controllers\PagesController::class,
        'action' => 'getStaticPage',
    ]);

    $route->get('pages/info', [
        'controller' => \Acme\Demo\Pages\Controllers\PagesController::class,
        'action' => 'info',
    ]);

    $route->get('pages/front', [
        'middlewares' => [
            'Acme\\Demo\\Base\\Middlewares\\StringReplacements' => [(5)],
            'Acme\\Demo\\Base\\Middlewares\\MakeView',
        ],
        'controller' => 'Acme\Demo\\Pages\Controllers\PagesController',
        'action' => 'frontController'
    ]);
});
