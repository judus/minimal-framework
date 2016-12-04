<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require "../vendor/autoload.php";
require "../helpers/common.php";


/**
 * Example 1
 * /
new \Maduser\Minimal\Base\Core\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
]);
// exits PHP

/**
 * Example 2
 * will do the same as example 1
 */

$benchmark = new \Maduser\Minimal\Base\Libraries\Benchmark();

$benchmark->mark('Start');

$minimal = new \Maduser\Minimal\Base\Core\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
    'app' => 'app',
    'config' => 'config/config.php',
    'bindings' => 'config/bindings.php',
    'providers' => 'config/providers.php',
    'routes' => 'config/routes.php',
], true);

$benchmark->mark('Minimal instantiated');

$benchmark->mark('Registering configs');

$minimal->load();

$benchmark->mark('Ready');

/** @var \Maduser\Minimal\Base\Core\Request $request */
$request = $minimal->getRequest();

/** @var \Maduser\Minimal\Base\Core\Router $router */
$router = $minimal->getRouter();

$benchmark->mark('Resolving route');

/** @var \Maduser\Minimal\Base\Core\Route $route */
$route = $router->getRoute($request->getUriString());

$benchmark->mark('Route resolved');

/** @var \Maduser\Minimal\Base\Core\Middleware $middleware */
$middleware = $minimal->getMiddleware($route->getMiddlewares());

$benchmark->mark('Middleware before start');

/** @var mixed $result */
$result = $middleware->dispatch(function () use ($minimal, $route, $benchmark) {

    $benchmark->mark('Middleware before end');

    $benchmark->mark('FrontController start');

    $result = $minimal->getFrontController()->dispatch($route)->getResult();

    $benchmark->mark('FrontController end');

    $benchmark->mark('Middleware after start');

    return $result;
});

$benchmark->mark('Middleware after end');

$benchmark->mark('Preparing the response');

/** @var \Maduser\Minimal\Base\Core\Response $response */
$response = $minimal->getResponse();

$response->prepare($result);

$benchmark->mark('Ready to send response');

$response->setContent(
    $benchmark->addBenchmarkInfo($response->getContent(), '</footer>')
);

$response->sendPrepared();

$minimal->exit();

// adios