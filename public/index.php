<?php namespace Maduser\Minimal\Facades;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../helpers/common.php";

/**
 * Example 1
 *
 * If you're happy with defaults, then you're in luck.
 * All you need is this: new \Maduser\Minimal\Apps\Minimal();
 * But for the demo:
 */
$minimal = new \Maduser\Minimal\Apps\Minimal();

$minimal->exit();

/**
 * Example 2
 *
 * Same as Example 1, but with a config array
 */
$minimal = new \Maduser\Minimal\Apps\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
    'app' => 'app',
    'config' => 'config/env.php',
    'bindings' => 'config/bindings.php',
    'providers' => 'config/providers.php',
    'routes' => 'config/routes.php',
]);

$minimal->exit();

/**
 * Example 3
 *
 * If you don't want to use the routes config file and rather start coding
 * right away in the index.php, then use the App facade with a closure:
 */
App::respond(function () {

    Router::get('array', function () {
        return Config::items();
    });

    Router::get('object', function () {
        return new Collection();
    });


    Router::get('database', function () {
        if (PDO::connection(Config::item('database'))) {
            return 'Successfully connected to database';
        }
    });

})->exit();

/**
 * Example 4
 *
 * Same as before, but with a config array
 */
App::respond([
    'path' => __DIR__,
    'config' => __DIR__ . '/../config/env.php',
    'bindings' => __DIR__ . '/../config/bindings.php',
    'providers' => __DIR__ . '/../config/providers.php',
    'routes' => __DIR__ . '/../config/routes.php',
    'modules' => __DIR__ . '/../config/modules.php',
], function () {

    Router::get('array', function () {
        return Config::items();
    });

    // ...

})->exit();

/**
 * Example 5
 *
 * You want full control over the order of things and to stuff in between.
 * Mind the second parameter in new Minimal($config, true), which tells
 * to return the instance instead of doing what Minimal does.
 */

/** @var \Maduser\Minimal\Http\Request $request */
/** @var \Maduser\Minimal\Http\Response $response */
/** @var \Maduser\Minimal\Routers\Router $router */
/** @var \Maduser\Minimal\Routers\Route $route */
/** @var \Maduser\Minimal\Middlewares\Middleware $middleware */
/** @var mixed $result */

$benchmark = new \Maduser\Minimal\Benchmark\Benchmark();

$benchmark->mark('Start');

$minimal = new \Maduser\Minimal\Apps\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
    'app' => 'app',
    'config' => 'config/env.php',
    'bindings' => 'config/bindings.php',
    'providers' => 'config/providers.php',
    'routes' => 'config/routes.php',
], true);

$benchmark->mark('Minimal instantiated');

$benchmark->mark('Registering configs');

$minimal->load();

$benchmark->mark('Ready');

$request = $minimal->getRequest();

$router = $minimal->getRouter();

$benchmark->mark('Resolving route');

$route = $router->getRoute($request->getUriString());
$benchmark->mark('Route resolved');

$middleware = $minimal->getMiddleware($route->getMiddlewares());

$benchmark->mark('Middleware before start');

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

$response = $minimal->getResponse();

$response->prepare($result);

$benchmark->mark('Ready to send response');

$response->setContent(
    $benchmark->addBenchmarkInfo($response->getContent(), '</footer>')
);

$response->sendPrepared();

$minimal->exit();
