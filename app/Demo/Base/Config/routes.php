<?php

/** @var \Maduser\Minimal\Routers\Router $router */

/**
 * Direct output
 *
 * Routes with closure are executed instantly if method and uri match, further
 * application logic is discarded
 */
$router->get('hello/(:any)/(:any)', function ($firstname, $lastname) {
    return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

/**
 * Using controllers
 */
$router->get('welcome/(:any)/(:any)',
    'Acme\\Demo\\Base\\Controllers\\YourController@yourMethod');


// Example: file download
/** @var \Maduser\Minimal\Http\Response $response */
$router->get('download/pdf', function () use ($response) {
    $response->header('Content-Type: application/pdf');
    $response->header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});

// Example: caching
$router->get('huge/data/table', [
    'middlewares' => ['Acme\\Demo\\Base\\Middlewares\\Cache' => [10]],
    // Cache for 10sec
    'controller' => 'Acme\\Demo\\Base\\Controllers\\YourController',
    'action' => 'timeConsumingAction'
]);


$router->get('lorem', [
    'middlewares' => ['Acme\\Demo\\Base\\Middlewares\\StringReplacements'],
    'controller' => 'Acme\\Demo\\Base\\Controllers\\YourController',
    'action' => 'loremIpsum'
]);

if (!$router->exists('demos', 'GET')) {
    $router->get('demos', function () {
        return (string)new \Acme\Demo\Base\Models\Navigation();
    });
}