<?php

/** @var \Maduser\Minimal\Base\Core\Router $route */

$route->group([
    'middlewares' => ['Acme\\Middlewares\\Cache' => [(10)]]
], function() use ($route) {

    $route->get('module/pages/(:any)', [
        'controller' => 'Acme\\Pages\\Controllers\\PagesController',
        'action' => 'getStaticPage',
    ]);

    $route->get('module/pages/info',
        'Acme\\Pages\\Controllers\\PagesController@info');
});
