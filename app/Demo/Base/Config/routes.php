<?php

/** @var \Maduser\Minimal\Routers\Router $route */

/**
 * Direct output
 *
 * Routes with closure are executed instantly if method and uri match, further
 * application logic is discarded
 */
$route->get('hello/(:any)/(:any)', function ($firstname, $lastname) {
    return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

/**
 * Using controllers
 */
$route->get('welcome/(:any)/(:any)',
    'Acme\\Demo\\Base\\Controllers\\YourController@yourMethod');


// Example: file download
/** @var \Maduser\Minimal\Http\Response $response */
$route->get('download/pdf', function () use ($response) {
    $response->header('Content-Type: application/pdf');
    $response->header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});

// Example: caching
$route->get('huge/data/table', [
    'middlewares' => ['Acme\\Demo\\Base\\Middlewares\\Cache' => [10]],
    // Cache for 10sec
    'controller' => 'Acme\\Demo\\Base\\Controllers\\YourController',
    'action' => 'timeConsumingAction'
]);


$route->get('lorem', [
    'middlewares' => ['Acme\\Demo\\Base\\Middlewares\\StringReplacements'],
    'controller' => 'Acme\\Demo\\Base\\Controllers\\YourController',
    'action' => 'loremIpsum'
]);

if (!$route->exists('demos', 'GET')) {
    $route->get('demos', function () {
        return (string)new \Acme\Demo\Base\Models\Navigation();
    });
}