<?php

/** @var \Maduser\Minimal\Base\Core\Router $route */

$route->get('assets/(:any)', [
    'controller' => 'Acme\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
