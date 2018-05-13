<?php

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Response;
use Maduser\Minimal\Framework\Facades\Router;

/**
 * Direct output
 *
 * Routes with closure are executed instantly if method and uri match, further
 * application logic is discarded
 */
Router::get('hello/(:any)/(:any)', function ($firstname, $lastname) {
    return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

/**
 * Using controllers
 */
Router::get('welcome/(:any)/(:any)',
    'App\\Demo\\Base\\Controllers\\YourController@yourMethod');


// Example: file download
/** @var \Maduser\Minimal\Http\Response $response */
Router::get('download/pdf', function() {
    Response::header('Content-Type: application/pdf');
    Response::header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});

// Example: caching
Router::get('huge/data/table', [
    'middlewares' => ['App\\Demo\\Base\\Middlewares\\Cache' => [10]],
    // Cache for 10sec
    'controller' => 'App\\Demo\\Base\\Controllers\\YourController',
    'action' => 'timeConsumingAction'
]);

Router::get('lorem', [
    'middlewares' => ['App\\Demo\\Base\\Middlewares\\StringReplacements'],
    'controller' => 'App\\Demo\\Base\\Controllers\\YourController',
    'action' => 'loremIpsum'
]);

Router::get('route-execution', function() {
    return App::execute('lorem');
});

if (!Router::exists('demos', 'GET')) {

    Router::get('demos', function () {
        return (string) new \App\Demo\Base\Models\Info(App::getInstance());
    });
}