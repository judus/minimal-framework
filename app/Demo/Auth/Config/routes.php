<?php

/** @var \Maduser\Minimal\Routers\Router $route */

/**
 * Route groups
 */
$route->group([

    // Prefixes all urls in the group with 'auth/'
    'uriPrefix' => 'auth',

    // Define the class namespace for all routes in this group
    // Will be prefixed to the controllers
    'namespace' => 'Acme\\Demo\\Auth\\Controllers\\'

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
            'Acme\\Demo\\Base\\Middlewares\\CheckPermission',
            // Send a email to the administrator
            'Acme\\Demo\\Base\\Middlewares\\ReportAccess',
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

    });

});