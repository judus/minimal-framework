<?php

/** @var \Maduser\Minimal\Routing\Router $router */

$router->get('assets/(:any)', [
    'controller' => 'App\\Demo\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
