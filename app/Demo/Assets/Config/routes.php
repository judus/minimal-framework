<?php

use Maduser\Minimal\Framework\Facades\Router;

Router::get('assets/(:any)', [
    'controller' => 'App\\Demo\\Assets\\Controllers\\AssetsController',
    'action' => 'getAsset'
]);
