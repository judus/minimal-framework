<?php

use Maduser\Minimal\Framework\Facades\Router;

Router::get('assets/(:segment)/(:any)', [
    'controller' => 'App\\Demo\\Assets\\Controllers\\AssetsController',
    'action' => 'serve'
]);
