<?php

/** @var \Maduser\Minimal\Routers\Router $router */

$router->group([
    'middlewares' => [
        'Acme\\Demo\\Base\\Middlewares\\Cache' => [(5)],
    ]
], function() use ($router) {

    $router->get('pages/(:any)', [
        'controller' => \Acme\Demo\Pages\Controllers\PagesController::class,
        'action' => 'getStaticPage',
    ]);

    $router->get('pages/info', [
        'controller' => \Acme\Demo\Pages\Controllers\PagesController::class,
        'action' => 'info',
    ]);

    $router->get('pages/front', [
        'middlewares' => [
            'Acme\\Demo\\Base\\Middlewares\\StringReplacements' => [(5)],
            'Acme\\Demo\\Base\\Middlewares\\MakeView',
        ],
        'controller' => 'Acme\Demo\\Pages\Controllers\PagesController',
        'action' => 'frontController'
    ]);
});
