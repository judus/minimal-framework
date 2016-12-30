<?php

/** @var \Maduser\Minimal\Base\Core\Router $route */

$route->group([
    'middlewares' => ['Acme\\Middlewares\\Cache' => [(10)]]
], function() use ($route) {

    $route->get('pages/(:any)', [
        'controller' => 'Acme\\Pages\\Controllers\\PagesController',
        'action' => 'getStaticPage',
    ]);

    $route->get('pages/info',
        'Acme\\Pages\\Controllers\\PagesController@info');
});
