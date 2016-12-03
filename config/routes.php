<?php

/** @var \Maduser\Minimal\Base\Core\Router $route */

// Direct output
$route->get('/', function () {
    return 'Hello from Minimal!';
});

// Using controller and method
$route->get('pages/(:any)', 'Acme\\Pages\\Controllers\\PagesController@getStaticPage');
$route->get('pages/info', 'Acme\\Pages\\Controllers\\PagesController@info');

$route->get('contact', 'Maduser\Minimal\\Base\\Controllers\\PagesController@contact');
$route->get('welcome/(:any)', 'Maduser\Minimal\\Base\\Controllers\\PagesController@welcome');
$route->get('welcome', 'Maduser\Minimal\\Base\\Controllers\\PagesController@welcome');
$route->get('page/welcome/(:any)/(:any)', 'Maduser\Minimal\\Base\\Controllers\\PagesController@welcome');
$route->get('page/welcome/(:any)', 'Maduser\Minimal\\Base\\Controllers\\PagesController@welcome');
$route->get('page/(:any)', 'Maduser\Minimal\\Base\\Controllers\\PagesController@getStaticPage');

$route->get('page/info', [
    'controller' => 'Maduser\Minimal\\Base\\Controllers\\PagesController',
    'action' => 'info',
    'middlewares' => ['Maduser\Minimal\\Base\\Middlewares\\Cache' => [8]]
]);

// Display dev info
$route->get('info', 'Maduser\Minimal\\Base\\Controllers\\PagesController@info');

/**
 * Grouped routes example
 */
$route->group([
    // Define the class namespace for all routes in this group
    // Will be prefixed to the controllers
    'namespace' => 'Maduser\\Minimal\\Base\\Controllers\\'
], function () use ($route) {

    /**
     * Subgroup with url prefix and middleware
     */
    $route->group([
        // Prefixes all urls in the group with 'auth/'
        'uriPrefix' => 'auth',
        // What should be done when accessing these routes
        'middlewares' => [
            // Check if the client is authorized to access this routes
            'Maduser\Minimal\\Base\\Middlewares\\CheckPermission',
            // Send a email to the administrator
            'Maduser\Minimal\\Base\\Middlewares\\ReportAccess',
        ]
    ], function () use ($route) {

        $route->get('users', [
            'controller' => 'UsersController',
            'action' => 'listUsers' // Show a list of users
        ]);

        $route->get('users/create', [
            'controller' => 'UsersController',
            'action' => 'create' // Show a empty user form
        ]);

        $route->post('users', [
            'controller' => 'UsersController',
            'action' => 'saveAsNew' // Save a new user
        ]);

        $route->get('users/edit/(:num)', [
            'controller' => 'UsersController',
            'action' => 'edit' // Show a form with user id = (:num)
        ]);

        $route->put('users/(:num)', [
            'controller' => 'UsersController',
            'action' => 'saveExistingUser' // Save user with id = (:num)
        ]);

        $route->delete('users/(:num)', [
            'controller' => 'UsersController',
            'action' => 'deleteUser' // Delete user with id = (:num)
        ]);

        /**
         * Example with overrides and custom values
         */
        $route->get('register', [
            // Override namespace for this route
            'namespace' => 'Maduser\\Minimal\\Modules\\Auth\\',
            // Disable the middleware for this route
            'middlewares' => null,
            // Add a custom value
            'module-path' => 'modules/auth',
            // Use controller
            'controller' => 'RegisterController',
            // Action
            'action' => 'showRegisterForm'
        ]);

    });

});

/**
 *  Direct responses
 */

// Direct output
$route->get('hello/(:any)/(:any)', function ($value1, $value2) {
    return 'Hello ' . $value1 . ' ' . $value2 . '!';
});

// Advanced responses
$route->get('download/pdf', function ($value1, $value2) use ($response) {
    $response->addHeader('Content-Type: application/pdf');
    $response->addHeader('Content-Disposition: attachment; filename="downloaded.pdf"');
    $response->setContent(readfile('original.pdf'));
    $response->send();
});

// Caching the output
$route->get('huge/data/table', [
    // keep in cache for day: (60*60*24)
    // keep in cache forever: -1
    // disable cache: 0 or null
    'cache' => (60 * 60 * 24),
    'namespace' => 'Maduser\\Minimal\\Base\\Controllers',
    'controller' => 'UsersController',
    'action' => 'timeConsumingAction'
]);


// Catch all
//$route->get('(:any)', 'Maduser\Minimal\\Base\\Controllers\\PagesController@getPage');

// TODO: Catch all in Closure
/* Problem: Closure are executed in registration, before the uri matching loop
$route->get('(:any)', function ($one) use ($view, $response) {
    $view->setBaseDir('../resources/views');
    $view->setTheme('my-theme');
    $view->setViewDir('main');
    $view->setView($one);

    if (!file_exists($view->getFullViewPath())) {
        $response->setHeader('HTTP/1.1 404 File not found');
        $content = '<h1>404 File not found</h1>';
        $content.= $view->getFullViewPath();
        $response->setContent($content);

        $response->send()->exit();
    }

    return $view->render(null, ['content' => $one]);
});
*/

