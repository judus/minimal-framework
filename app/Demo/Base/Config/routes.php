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

$route->get('demos', function () use ($route) {
    $routes = $route->getRoutes();
    $html = '';
    /** @var \Maduser\Minimal\Routers\Route $route */
    foreach ($routes->get('GET') as $route) {
        $params = $route->getUriParameters();
        $args = [];
        foreach ($params as $param) {
            if ($param == '(:num)') {
                $args[] = rand(1, 9);
            } else {
                $args[] = substr(md5(microtime()), rand(0, 26), 3);
            }
        }

        $uri = call_user_func_array([$route, 'uri'], $args);
        $text = $route->getUriPattern();
        $html .= '<li><a href="' . $uri . '">' . $text . '</a></li>';
    }

    return '<ul>' . $html . '</ul>';
});