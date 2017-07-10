<?php

if (version_compare(phpversion(), '7.0.0', '<')) {
    die('Requires PHP version > 7.0.0');
}

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../helpers/common.php";
/*
require "../libraries/Translation.php";

/**
 * Example 1
 * /
new \Maduser\Minimal\Core\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
]);
// exits PHP

/**
 * Example 2
 * will do the same as example 1
 */

$benchmark = new \Maduser\Minimal\Libraries\Benchmark\Benchmark();

$benchmark->mark('Start');

$minimal = new \Maduser\Minimal\Core\Minimal([
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

/** @var \Maduser\Minimal\Core\Request $request */
$request = $minimal->getRequest();

/** @var \Maduser\Minimal\Core\Router $router */
$router = $minimal->getRouter();



$benchmark->mark('Resolving route');

/** @var \Maduser\Minimal\Core\Route $route */
$route = $router->getRoute($request->getUriString());
$benchmark->mark('Route resolved');

/** @var \Maduser\Minimal\Core\Middleware $middleware */
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

/** @var \Maduser\Minimal\Core\Response $response */
$response = $minimal->getResponse();

$response->prepare($result);

$benchmark->mark('Ready to send response');

$response->setContent(
    $benchmark->addBenchmarkInfo($response->getContent(), '</footer>')
);

$response->sendPrepared();

$minimal->exit();

// adios