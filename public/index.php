<?php namespace Maduser\Minimal\Facades;

if (version_compare(phpversion(), '7.0.0', '<')) {
    die('Requires PHP version > 7.0.0');
}

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../helpers/common.php";

App::respond([
    'path' => __DIR__,
    'config' => __DIR__ . '/../config/env.php',
    'bindings' => __DIR__ . '/../config/bindings.php',
    'providers' => __DIR__ . '/../config/providers.php',
    'routes' => __DIR__ . '/../config/routes.php',
    'modules' => __DIR__ . '/../config/modules.php',
], function () {

    Router::get('array', function () {
        return Config::getItems();
    });

    Router::get('object', function () {
        return new Collection();
    });

    Router::get('lorem', [
        'middlewares' => ['Acme\\Middlewares\\StringReplacements'],
        'controller' => 'Acme\\Controllers\\YourController',
        'action' => 'loremIpsum'
    ]);

    Router::get('database', function () {
        if (PDO::connection(Config::item('database'))) {
            return 'Successfully connected to database';
        }
    });

    Router::get('include', function () {
        include 'app/modules/Demo/demo.php';
    });
});

exit();

/*
require "../libraries/Translation.php";

/**
 * Example 1
 * /


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