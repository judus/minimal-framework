<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require "../vendor/autoload.php";
require "../helpers/common.php";


/**
 * Example 1
 */

new \Maduser\Minimal\Base\Core\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
]);
// exits PHP

/**
 * Example 2
 * will do the same as example 1
 */
$minimal = new \Maduser\Minimal\Base\Core\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
    'app' => 'app',
    'config' => 'config/config.php',
    'bindings' => 'config/bindings.php',
    'providers' => 'config/providers.php',
    'routes' => 'config/routes.php',
], true);

$minimal->load();
$request = $minimal->getRequest();
$router = $minimal->getRouter();
$uriString = $request->getUriString();
$route = $router->getRoute($uriString);
$frontController = $minimal->getFrontController();
$frontController->dispatch($route);
$minimal->setResult($frontController->getControllerResult());
$minimal->respond();
$minimal->exit();
// exits PHP