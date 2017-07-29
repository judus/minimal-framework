<?php

/** @var \Maduser\Minimal\Routers\Router $route */

$route->get('assets/(:any)', [
    'controller' => 'Acme\\Demo\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
