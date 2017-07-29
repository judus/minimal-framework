<?php

/** @var \Maduser\Minimal\Routers\Router $route */

$router->get('assets/(:any)', [
    'controller' => 'Acme\\Demo\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
