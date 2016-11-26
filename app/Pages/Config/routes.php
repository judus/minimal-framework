<?php

/** @var \Maduser\Minimal\Base\Core\Router $route */

$route->get('module/pages/(:any)', 'Acme\\Pages\\Controllers\\PagesController@getStaticPage');
$route->get('module/pages/info', 'Acme\\Pages\\Controllers\\PagesController@info');
