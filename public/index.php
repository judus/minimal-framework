<?php namespace Maduser\Minimal\Framework\Facades;

require __DIR__ . "/../vendor/autoload.php";

/**
 * Example 1
 *
 * If you're happy with the defaults
 */
App::dispatch();

/**
 * Example 2 - OBSOLETE SINCE MINIMAL v.0.4.0 !!
 * TODO: re-implement if possible
 *
 * Same as before, but with a config array
 */
/*
App::dispatch([
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

});
*/

/**
 * Example 3
 *
 * If you are in a hurry and you don't want to use the routes config or other
 * config file, although they still will be loaded if they exists, you could
 * start coding like so:
 */
use Maduser\Minimal\Database\DB;
use App\Demo\ORM\Entities\Role;
use App\Demo\ORM\Entities\User;

App::dispatch(function () {

    // Respond on GET / request with a header location
    Router::get('/', function () {
        Response::redirect(http() . 'demos');
    });


    // Register all modules configs and routes within modules path
    Modules::register('Demo/*');

    // Respond on GET request with uri parameters
    Router::get('hello/(:any)/(:num)', function ($any, $num) {
        return 'Hello ' . $any . ' ' . $num;
    });

    // Respond on POST request
    Router::post('/post', function () {
        return Request::post();
    });

    // Translations
    Router::get('translator', function () {

        $str = '"Guete Morge" => (auto) ' . __('Guete Morge');
        $str .= '<br>"Guete Morge" => (en) ' . __('Guete Morge', 'en');
        $str .= '<br>"Guete Morge" => (de) ' . __('Guete Morge', 'de');
        $str .= '<br>"Guete Morge" => (fr) ' . __('Guete Morge', 'fr');
        $str .= '<br>"Guete Morge" => (it) ' . __('Guete Morge', 'it');
        $str .= '<br>"Guete Morge" => (rm) ' . __('Guete Morge', 'rm');

        return $str;
    });

    // Respond with a view
    Router::get('view', function () {
        return View::render(path('views') . 'pages/my-view', [
            'title' => 'Hello',
            'content' => lorem()
        ]);
    });

    // Test the database connection
    Router::get('database', function () {
        DB::connections(Config::database());
        return 'Successfully connected to database';
    });

    // Route group
    Router::group([
        'uriPrefix' => 'route-groups',
        'namespace' => 'App\\Demo\\Base\\Controllers\\',
        'middlewares' => [
            'App\\Demo\\Base\\Middlewares\\CheckPermission',
            'App\\Demo\\Base\\Middlewares\\ReportAccess',
        ]
    ], function () {

        // Responds to GET route-groups/controller-action/with/middlewares'
        Router::get('controller-action/with/middlewares', [
            'middlewares' => ['App\\Demo\\Base\\Middlewares\\Cache' => [10]],
            'controller' => 'YourController',
            'action' => 'timeConsumingAction'
        ]);

        // Do database stuff
        Router::get('users', function () {

            // Database connection for all the routes in this group
            DB::connections(Config::database());

            // Import namespaces of the models on top of file to make this work

            // Truncate tables
            Role::instance()->truncate();
            User::instance()->truncate();

            // Create 2 new roles
            Role::create([['name' => 'admin'], ['name' => 'member']]);

            // Get all the roles
            $roles = Role::all();

            // Create a user
            $user = User::create(['username' => 'john']);

            // Assign all roles to this user
            $user->roles()->attach($roles);

            // Get the first username 'john' with his roles
            return $user->with('roles')->where(['username', 'john'])->first();
        });

        // ... subgroups are possible ...

    });
});

/**
 * Example 4 - OBSOLETE SINCE MINIMAL v.0.4.0 !!
 * TODO: re-implement if possible
 *
 * Same as example 1 but without facade
 * If you're happy with the defaults.
 */
//new \Maduser\Minimal\Framework\Minimal();

/**
 * Example 5 - OBSOLETE SINCE MINIMAL v.0.4.0 !!
 * TODO: re-implement if possible
 *
 * Same as example 2 but without facade
 * Same as Example 1, but with a config array
 */
/*
$minimal = new \Maduser\Minimal\Framework\Minimal([
    'basepath' => realpath(__DIR__ . '/../'),
    'app' => 'app',
    'config' => 'config/environment.php',
    'bindings' => 'config/bindings.php',
    'providers' => 'config/providers.php',
    'routes' => 'config/routes.php',
]);
*/

/**
 * Example 6 - OBSOLETE SINCE MINIMAL v.0.4.0 !!
 * Since the event based system, the following could be achieved with event
 * subscribers. For even more control define a custom applicationProvider and
 * load it with: App::use('MyCustomAppProvider::class');
 * TODO: re-implement if possible
 *
 * You want full control over the order of things and to stuff in between.
 * Mind the second parameter in new Minimal($config, true), which tells
 * to return the instance instead of doing what Minimal does.
 */

/** @var \Maduser\Minimal\Http\Request $request */
/** @var \Maduser\Minimal\Http\Response $response */
/** @var \Maduser\Minimal\Routing\Router $router */
/** @var \Maduser\Minimal\Routing\Route $route */
/** @var \Maduser\Minimal\Middlewares\Middleware $middleware */
/** @var mixed $result */

$benchmark = new \Maduser\Minimal\Benchmark\Benchmark();

$benchmark->mark('Start');

$minimal = new \Maduser\Minimal\Framework\Minimal([
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

$minimal->terminate();
