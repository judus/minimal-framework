<?php

/** @var \Maduser\Minimal\Routers\Router $route */

$router->get('assets/(:any)', [
    'controller' => 'App\\Demo\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
