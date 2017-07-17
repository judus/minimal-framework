<?php

/** @var \Maduser\Minimal\Routers\Router $route */

$route->get('assets/(:any)', [
    'controller' => 'Acme\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
