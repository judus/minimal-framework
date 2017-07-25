<?php

/** @var \Maduser\Minimal\Routers\Router $route */

use Maduser\Minimal\Database\Comment;
use Maduser\Minimal\Database\Connectors\PDO;
use Maduser\Minimal\Database\Post;
use Maduser\Minimal\Database\Profile;
use Maduser\Minimal\Database\Role;
use Maduser\Minimal\Database\User;
use Maduser\Minimal\Database\Usertype;
use Maduser\Minimal\Facades\Config;

/**
 * Direct output
 *
 * Routes with closure are executed instantly if method and uri match, further
 * application logic is discarded
 */
$route->get('/', function () {
    return 'Hello from Minimal!';
});

$route->get('hello/(:any)/(:any)', function ($firstname, $lastname) {
    return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

/**
 * Using controllers
 */
$route->get('welcome/(:any)/(:any)', 'Acme\\Controllers\\YourController@yourMethod');

/**
 * Route groups
 */
$route->group([

    // Prefixes all urls in the group with 'auth/'
    'uriPrefix' => 'auth',

    // Define the class namespace for all routes in this group
    // Will be prefixed to the controllers
    'namespace' => 'Acme\\Controllers\\'

], function () use ($route) {

    // GET request: 'auth/login'
    // Controller 'Acme\\Controllers\AuthController
    $route->get('login', [
        'controller' => 'AuthController',
        'action' => 'loginForm' // Show the login form
    ]);

    // POST request: 'auth/login'
    // Controller 'Acme\\Controllers\AuthController
    $route->post('login', [
        'controller' => 'AuthController',
        'action' => 'login' // Login the user
    ]);

    // GET request: 'auth/logout'
    // Controller 'Acme\\Controllers\AuthController
    $route->get('logout', [
        'controller' => 'AuthController',
        'action' => 'logout' // Logout the user
    ]);

    /**
     * Subgroup with middlewares
     */
    $route->group([
        // Middlewares apply to all route in this (sub)group
        'middlewares' => [
            // Check if the client is authorised to access these routes
            'Acme\\Middlewares\\CheckPermission',
            // Send a email to the administrator
            'Acme\\Middlewares\\ReportAccess',
        ]
    ], function () use ($route) {

        // No access to these routes if middleware CheckPermission fails
        // Middleware ReportAccess reports all access to these routes

        // GET request: 'auth/users'
        // Controller 'Acme\\Controllers\UserController
        $route->get('users', [
            'controller' => 'UserController',
            'action' => 'list' // Show a list of users
        ]);

        // GET request: 'auth/users/create'
        // Controller 'Acme\\Controllers\UserController
        $route->get('users/create', [
            'controller' => 'UserController',
            'action' => 'createForm' // Show a empty user form
        ]);

        // GET request: 'auth/users/edit'
        // Controller 'Acme\\Controllers\UserController
        $route->get('users/edit/(:num)', [
            'controller' => 'UserController',
            'action' => 'editForm' // Show a edit form for user (:num)
        ]);

        // etc...
    });
});

// Example: file download
$route->get('download/pdf', function () use ($response) {
    $response->header('Content-Type: application/pdf');
    $response->header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});

// Example: caching
$route->get('huge/data/table', [
    'middlewares' => ['Acme\\Middlewares\\Cache' => [10]], // Cache for 10sec
    'controller' => 'Acme\\Controllers\\YourController',
    'action' => 'timeConsumingAction'
]);


