~~This is a (working) proof of concept.~~ 
This is now a work in progress. Busy, busy...

# Minimal PHP Framework

Minimal is a web application framework.

## Requirements

1. PHP version >= 7.0
2. composer

## Install
```bash
$ composer create-project minimal/framework
```

## Usage

[Routing](#routing) | [Middlewares](#middlewares) | [Providers](#providers) | [Dependency Injection](#dependency-injection) | [Views](#views) | [Assets](#assets) | [Modules](#modules) | [CLI](#cli)

### Routing

##### Direct output:
```php
// in config/routes.php

Router::get('hello/(:any)/(:any)', function($firstname, $lastname) {
    return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

// Router::get() responds to GET requests
// Router::post() responds to POST requests
// Router::put() ...you get it
// Router::patch()
// Router::delete()

// (:any) match letters and integer
// (:num) match integer only
```
http://localhost/hello/julien/duseyau
-> Hello Julien Duseyau

##### Using controllers
```php
// in config/routes.php 

Router::get('hello/(:any)/(:any)', 'App\\Controllers\\YourController@yourMethod')
```
```php
// in app/Controllers/YourController.php

class App\Controllers\YourController
{
    public function yourMethod($name, $lastname)
    {
        return 'Hello ' . ucfirst($name) . ' ' . ucfirst($lastname);
    }
}
```
http://localhost/hello/julien/duseyau
-> Hello Julien Duseyau

##### Route groups
```php
// in config/routes.php

Router::group([

    // Prefixes all urls in the group with 'auth/'
    'uriPrefix' => 'auth',

    // Define the class namespace for all routes in this group
    // Will be prefixed to the controllers
    'namespace' => 'App\\Controllers\\'

], function () use ($route) {

    // GET request: 'auth/login'
    // Controller 'App\\Controllers\AuthController
    Router::get('login', [
        'controller' => 'AuthController',
        'action' => 'loginForm' // Show the login form
    ]);

    // POST request: 'auth/login'
    // Controller 'App\\Controllers\AuthController
    Router::post('login', [
        'controller' => 'AuthController',
        'action' => 'login' // Login the user
    ]);

    // GET request: 'auth/logout'
    // Controller 'App\\Controllers\AuthController
    Router::get('logout', [
        'controller' => 'AuthController',
        'action' => 'logout' // Logout the user
    ]);

    /**
     * Subgroup with middlewares
     */
    Router::group([
    
        // Middlewares apply to all route in this (sub)group
        'middlewares' => [
            // Check if the client is authorised to access these routes
            'App\\Middlewares\\CheckPermission',
            // Log or send a access report
            'App\\Middlewares\\ReportAccess',
        ]
    ], function () use ($route) {

        // No access to these routes if middleware CheckPermission fails
        // Middleware ReportAccess reports all access to these routes

        // GET request: 'auth/users'
        // Controller 'App\\Controllers\UserController
        Router::get('users', [
            'controller' => 'UserController',
            'action' => 'list' // Show a list of users
        ]);

        // GET request: 'auth/users/create'
        // Controller 'App\\Controllers\UserController
        Router::get('users/create', [
            'controller' => 'UserController',
            'action' => 'createForm' // Show a empty user form
        ]);

        // GET request: 'auth/users/edit'
        // Controller 'App\\Controllers\UserController
        Router::get('users/edit/(:num)', [
            'controller' => 'UserController',
            'action' => 'editForm' // Show a edit form for user (:num)
        ]);

        // etc...
    });
});
```
##### File download
```php
Router::get('download/pdf', function () use ($response) {
    Response::header('Content-Type: application/pdf');
    Response::header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});
```
### Middlewares

```php
// in config/routes.php

Router::get('users', [
    'controller' => 'UsersController',
    'action' => 'list',
    'middlewares' => [
        // Check if the client is authorized to access this route
        'App\\Middlewares\\checkPermission',
        // Send a email to the administrator
        'App\\Middlewares\\ReportAccess',
        // Cache for x seconds
        'App\\Middlewares\\Cache' => [(1*1*10)]
    ]
]);
```

```php
// in app/Middlewares/CheckPermission.php

class CheckPermission implements MiddlewareInterface
{
    ...
        
    // Inject what you want, instance is created through
    // IOC::make() which injects any dependencies
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        RouteInterface $route
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
    }

    // Executed before dispatch
    public function before() {
        // If not authorised...
                
        // ... send appropriate response ...
        $this->response->addHeader();
        $this->response->setContent();
        $this->response->send()->exit();

        // ... or redirect to login page
        $this->response->redirect('login');

        // ... or set error and cancel dispatch
        $this->request->setError();
        return false;
    }
}
```

```php
// in app/Middlewares/Cache.php

class Cache implements MiddlewareInterface
{
    ...

    // Executed before dispatch
    public function before() {
        // return cached contents
    }
    
    // Executed after dispatch
    public function after() {
        // delete old cache
        // create new cache
    }
}
```

##### Standalone example
```php
// Array of middlewares
$middlewares = [
    'App\\Middlewares\\checkPermission',
    'App\\Middlewares\\ReportAccess',
    'App\\Middlewares\\Cache' => [(1*1*10)]
];

// The middleware controller
$middleware = new Maduser\Minimal\Middlewares\Middleware($middlewares);

// Wrap a task in middleware layers
$response = $middleware->dispatch(function() {
    // executes before() on each middleware layer here
    return 'the task, for example frontController->dispatch(),'
    // executes after() on each middleware layer here
});

```

### Providers

```php
// in config/providers.php

return [
    'App\\MyClass' => App\MyClassProvider::class, 
    'App\\MyOtherClassA' => App\MyOtherClassAProvider::class, 
    'App\\MyOtherClassB' => App\MyOtherClassBProvider::class, 
];
```

```php
// in app/MyClassProvider.php

class MyClassProvider extends Provider
{
    public function resolve()
    {
        // Do something before the class is instantiated
        $time = time();
        Assets::setPath()
        $settings = Config::item('settings');
        
        // return new instance
        /* return new MyClass($time, $settings); */ 
        
        // Make singleton and resolve dependencies
        return $this->singleton('MyClass', new App\\MyClass(
            IOC::resolve('App\\MyOtherClassA'),
            IOC::resolve('App\\MyOtherClassB'),
            $time,
            $settings
       ));
    }
}

// IOC::resolve('App\\MyOtherClassA')
// Resolves a class through a provider as defined in config/providers.php
```

### Dependency injection

Binding a interface implementation
```php
// in config/bindings.php

return [
    'App\\InterfaceA' => App\ClassA::class,
    'App\\InterfaceB' => App\ClassB::class',
];
```

```php
class MyClass
{
    private $classA;
    private $classB;
    
    public function __construct(InterfaceA $classA, InterfaceB $classB) {
        $this->classA = $classA;
        $this->classB = $classB;
    }
}

$MyClass = IOC::make(MyClass::class); 
// Does a reflection and injects the dependencies as defined in config/bindings.php
```

### Views
```php
// anywhere in your code

View::setBase('../resources/views/'); // Path from index.php
View::setTheme('my-theme'); // Set a subdir (optional)
View::setLayout('layouts/my-layout') // View wrapper

// Share a variable $title across all views
View::share('title', 'My title');  

// Set variables only for this view
View::set('viewValue1', 'someValue1')

View::render('pages/my-view', [
    'viewValue2' => 'someValue2', // Same as View::set()
    'viewValue3' => 'someValue3'  // Same as View::set()
]);
```

```html
<!-- resources/views/my-theme/layouts/my-layout.php -->

<!DOCTYPE html>
<html>
<head>
    <title><?=$title?></title>    
</head>
<body>
    <!-- Get the view -->
    <?= self::view() ?>    
</body>
</html>


<!-- resources/views/my-theme/main/my-view.php -->

<p><?= $viewValue1 ?></p>
<p><?= $viewValue2 ?></p>
```
```php
// Example of injection

class SomeController
{
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;        
        $this->view->setBaseDir('../resources/views');
    }

    public function someMethod()
    {
        return $this->view->render('my-view');
    }
}
```

### Assets
```php
Assets::setBase('../app/Pages/resources/assets'); // Set base dir of assets
Assets::setTheme('my-theme'); // Optional subdirectory
Assets::setCssDir('css'); // Directory for the css
Assets::setJsDir('js'); // Directory for the js
Assets::addCss(['normalize.css', 'main.css']); // Register css files
Assets::addJs(['vendor/modernizr-2.8.3.min.js'], 'top'); //Register js files with keyword
Assets::addJs(['plugins.js', 'main.js'], 'bottom'); // Register more js files with another keyword
Assets::addExternalJs(['https://code.jquery.com/jquery-3.1.0.min.js'], 'bottom'); // Js from CDN

/** @var Maduser\Minimal\Libraries\View\View $view */
Assets::addInlineScripts('jQueryFallback', function () use ($view) {
    return View::render('scripts/jquery-fallback', [], true);
});
```

The Assets class injected into the View class
```html
<!-- resources/views/my-theme/layouts/my-layout.php -->
<html>
<head>
    <title><?=$title?></title>

    <?= Assets::getCss() ?>
    <?= Assets::getJs('top') ?>
</head>
<body>
    <div class="content">
        ...
    </div>

    <?= Assets::getExternalJs('bottom') ?>
    <?= Assets::getInlineScripts('jQueryFallback') ?>
    <?= Assets::getJs('bottom') ?>
    <?= Assets::getInlineScripts() ?>
</body>
</html>
```
Outputs:
```html
<html>
<head>
    <title>My title</title>
    
    <link rel="stylesheet" href="assets/my-theme/css/normalize.css">
    <link rel="stylesheet" href="assets/my-theme/css/main.css">
    <script src="assets/my-theme/js/vendor/modernizr-2.8.3.min.js" ></script>
</head>
<body>
    <div class="content">
        ...
    </div>

    <script src="https://code.jquery.com/jquery-3.1.0.min.js" ></script>
    <script>window.jQuery || document.write('...blablabla...')</script>

    <script src="assets/my-theme/js/plugins.js" ></script>
    <script src="assets/my-theme/js/main.js" ></script>
</body>
</html>
```

### Modules
See config/modules.php and example module in framework/app/Pages.

1. Create a folder your-module in the framework/app directory 
2. Copy & Paste framework/config and framework/resources to framework/app/your-module
3. Modify the config files accordingly or just empty them (you can't register the same route twice, it would throw a exception)
4. Register the new module in framework/config/modules.php: 
```php
// in framework/config/modules.php

Modules::register('your-module-dirname', [
    // optional config array
    'path' => 'app', // location of the module dir
    'routes' => 'app/YourModule/Http/routes.php',
    // ...more options
]);
```

### CLI
##### List all registered routes
```bash
$ php minimal routes

-----------------------------------------------------------------------------------------------------------------------------------------------------------
| Type | Pattern                 | Action                                               | Middlewares                                                     |
-----------------------------------------------------------------------------------------------------------------------------------------------------------
| GET  | /                       | <= Closure()                                         |                                                                 |
| GET  | /hello/(:any)/(:any)    | <= Closure()                                         |                                                                 |
| GET  | /welcome/(:any)/(:any)  | App\Controllers\YourController@yourMethod           |                                                                 |
| GET  | /auth/login             | App\Controllers\AuthController@loginForm            |                                                                 |
| POST | /auth/login             | App\Controllers\AuthController@login                |                                                                 |
| GET  | /auth/logout            | App\Controllers\AuthController@logout               |                                                                 |
| GET  | /auth/users             | App\Controllers\UserController@list                 | App\Middlewares\CheckPermission, App\Middlewares\ReportAccess |
| GET  | /auth/users/create      | App\Controllers\UserController@createForm           | App\Middlewares\CheckPermission, App\Middlewares\ReportAccess |
| GET  | /auth/users/edit/(:num) | App\Controllers\UserController@editForm             | App\Middlewares\CheckPermission, App\Middlewares\ReportAccess |
| GET  | /download/pdf           | <= Closure()                                         |                                                                 |
| GET  | /huge/data/table        | App\Controllers\YourController@timeConsumingAction  | App\Middlewares\Cache(10)                                      |
| GET  | /pages/(:any)           | App\Pages\Controllers\PagesController@getStaticPage | App\Middlewares\Cache(10)                                      |
| GET  | /pages/info             | App\Pages\Controllers\PagesController@info          | App\Middlewares\Cache(10)                                      |
| GET  | /assets/(:any)          | App\Assets\Controllers\AssetsController@getAsset    |                                                                 |
-----------------------------------------------------------------------------------------------------------------------------------------------------------
```
##### List all registered modules
```bash
$ php minimal modules

---------------------------------------------------------------------------------------------------------------------------------------------------------
| Name   | Path        | Config                       | Routes                       | Providers                       | Bindings                       |
---------------------------------------------------------------------------------------------------------------------------------------------------------
| Pages  | app/Pages/  | app/Pages/Config/config.php  | app/Pages/Config/routes.php  | app/Pages/Config/providers.php  | app/Pages/Config/bindings.php  |
| Assets | app/Assets/ | app/Assets/Config/config.php | app/Assets/Config/routes.php | app/Assets/Config/providers.php | app/Assets/Config/bindings.php |
---------------------------------------------------------------------------------------------------------------------------------------------------------
```
##### List all registered bindings
```bash
$ php minimal bindings
```

##### List all registered providers
```bash
$ php minimal providers
```

##### List all registered config
```bash
$ php minimal config
```

### Alternate usage with facades
```php
// in public/index.php

App::respond(function () {

    // Register all modules configs and routes within path
    Modules::register('Demo/*');

    // Respond on GET request
    Router::get('/', function () {
        return 'Hello from Minimal!';
    });

    // Respond on GET request with uri paramters
    Router::get('hello/(:any)/(:num)', function ($any, $num) {
        return 'Hello ' . $any . ' ' . $num ;
    });

    // Respond on POST request
    Router::post('/', function () {
        return Request::post();
    });

    // Respond with HTTP location
    Router::get('redirection', function () {
        Response::redirect('/');
    });

    // Respond with a view
    Router::get('view', function () {
        return View::render('fancy-html', ['param' => 'value']);
    });

    // Test the database connection
    Router::get('database', function () {
        PDO::connection(Config::item('database'));
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

            // Connect to database
            PDO::connection(Config::item('database'));

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
```

---
## Components

- [judus/minimal-assets](https://github.com/judus/minimal-assets)
- [judus/minimal-benchmark](https://github.com/judus/minimal-benchmark)
- [judus/minimal-cli](https://github.com/judus/minimal-cli)
- [judus/minimal-collections](https://github.com/judus/minimal-collections)
- [judus/minimal-config](https://github.com/judus/minimal-config)
- [judus/minimal-controllers](https://github.com/judus/minimal-controllers)
- [judus/minimal-database](https://github.com/judus/minimal-database)
- [judus/minimal-html](https://github.com/judus/minimal-html)
- [judus/minimal-http](https://github.com/judus/minimal-http)
- [judus/minimal-middlewares](https://github.com/judus/minimal-middlewares)
- [judus/minimal-minimal](https://github.com/judus/minimal-minimal)
- [judus/minimal-paths](https://github.com/judus/minimal-paths)
- [judus/minimal-presenters](https://github.com/judus/minimal-presenters)
- [judus/minimal-provider](https://github.com/judus/minimal-provider)
- [judus/minimal-routing](https://github.com/judus/minimal-routing)
- [judus/minimal-translation](https://github.com/judus/minimal-translation)
- [judus/minimal-views](https://github.com/judus/minimal-views)

---
#### TODOs
- Unit tests
- Api documentation

---

### About

- It was too cold for outdoor activities. (Winter 2016)
- It was too warm for physical activities. (Summer 2017)


### License

The Minimal framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
