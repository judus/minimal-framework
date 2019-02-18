# Minimal Framework

Minimal is a MVC web application framework for PHP.

```php
App::dispatch(function () {
    DB::connections(Config::database());
    
    Router::get('space-game/(:num)/(:num)', function ($characterId, $levelId) {
       return [
          Character::with('sprite', 'trait')->getById($characterId)->toArray(),
          LevelSpec::with('sprite', 'entity.trait')->getById($levelId)->toArray()
       ];
    });
}
```


---

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Introduction

The goal is of this framework is to provide basic and easy extensible functionality and to demonstrate some architectural concepts. 
If you need a full featured and rock solid framework for business application, go for another more established framework.

Key features:
- Build MVC-, REST-, CLI-APIs and apps and query databases with a ORM
- Take advantage of inversion of control and facades
- Easy install via command line and work out of the box
- No dependencies to third party libraries (except in development mode: PHPUnit, Symfony VarDumper)
- Most of the core components work standalone 
- Use plain PHP in the views/templates
  
##### NOTE 
- This is version v0.* changes are to be expected
  
##### Known bugs
- None that I am aware of, but the documentation might be out of date from time to time
   
## Requirements

1. PHP version >= 7.0
2. composer

## Install
```bash
$ composer create-project minimal/framework
```

Then point your server's document root to the public directory. 

If you use the PHP-builtin webserver then do:
```bash
$ cd public
$ php -S 0.0.0.0:8000 server.php
```

## Usage

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Quickstart example
```php
App::dispatch(function () {

    // Register additional services
    App::register(['Demo' => DemoServiceProvider::class]);

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

            // Connect to database
            DB::connections(Config::database());

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

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>


### Routing

##### Direct output:
```php
Router::get('hello/(:any)/(:any)', function($firstname, $lastname) {
    return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

// (:segment) match anything between two slashes
// (:any) match anything until next wildcard or end of uri
// (:num) match integer only
```
http://localhost/hello/julien/duseyau
-> Hello Julien Duseyau

```php
// Router::get() responds to GET requests
// Router::post() responds to POST requests
// Router::put() ...you get it
// Router::patch()
// Router::delete()

// 1st parameter: string uri pattern
// 2nd parameter: a closure with return sends a response to the client, a array
// of key/value pairs sets the attributes of the route object, which are:
//     'controller': the controller class to load,
//     'action':, the method to execute
//     'uriPrefix': a string that prefixes the uri pattern
//     'middlewares': a multidimensional array of middleware with optional params 
//     'params': array of values that will be injected to the method 
```

##### Using controllers
```php
Router::get(hello/(:any)/(:any)', 'App\\Demo\\Base\\Controllers\\YourController@yourMethod');
```
or
```php
Router::get(hello/(:any)/(:any), [
    'controller' => YourController::class,
    'action' => 'yourMethod'
]);
```
```php
class App\Demo\Base\Controllers\YourController
{
    public function yourMethod($name, $lastname)
    {
        return 'Hello ' . ucfirst($name) . ' ' . ucfirst($lastname);
    }
}
```
http://localhost/hello/julien/duseyau -> Hello Julien Duseyau

##### Route groups
```php
Router::group([

    // Prefixes all urls in the group with 'auth/'
    'uriPrefix' => 'auth',

    // Define the class namespace for all routes in this group
    // Will be prefixed to the controllers
    'namespace' => 'App\\Demo\\Auth\\Controllers\\'

], function () {

    // GET request: 'auth/login'
    // Controller 'App\\Demo\\Auth\\Controllers\AuthController
    Router::get('login', [
        'controller' => 'AuthController',
        'action' => 'loginForm' // Show the login form
    ]);

    // POST request: 'auth/login'
    // Controller 'App\\Demo\\Auth\\Controllers\AuthController
    Router::post('login', [
        'controller' => 'AuthController',
        'action' => 'login' // Login the user
    ]);

    /**
     * Subgroup with middlewares
     */
    Router::group([
    
        // Middlewares apply to all route in this (sub)group
        'middlewares' => [
            // Check if the client is authorised to access these routes
            'App\\Demo\\Auth\\Middlewares\\CheckPermission',
            // Log or send a access report
            'App\\Demo\\Auth\\Middlewares\\ReportAccess',
        ]
    ], function () {

        // No access to these routes if middleware CheckPermission fails

        // GET request: 'auth/users'
        // Controller 'App\\Demo\\Auth\\Controllers\UserController
        Router::get('users', [
            'controller' => 'UserController',
            'action' => 'list' // Show a list of users
        ]);

        // etc...

    });
});
```
##### File download
```php
Router::get('download/pdf', function () {
    Response::header('Content-Type: application/pdf');
    Response::header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});
```

##### Single route execution from anywhere
```php
$widget = App::execute('route/of/widget')
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Dependency injection

Binding a interface to a implementation is straight forward:
```php
App::addBindings([
    'App\\InterfaceA' => App\ClassA::class,
    'App\\InterfaceB' => App\ClassB::class,
    'App\\InterfaceC' => App\ClassC::class
]);
```
or in config/bindings.php
```php
return [
    'App\\InterfaceA' => \App\ClassA::class,
    'App\\InterfaceB' => \App\ClassB::class,
    'App\\InterfaceC' => \App\ClassC::class
];
```

```php
class ClassA {}

class ClassB {}

class ClassC
{
    public function __construct(InterfaceB $classB) {}
}

class MyClass
{
    public function __construct(InterfaceA $classA, InterfaceC $classC) {}
}
```

```php
$MyClass = App::make(MyClass::class); 
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Providers

```php
App::register([
    'MyService' => \App\MyService::class,
    'App\MyClass' => \App\MyClass::class, 
    'MyOtherClassA' => \App\MyOtherClassAFactory::class, 
    'any-key-name-will-do' => \App\MyOtherClassB::class, 
]);
```
or in config/providers.php
```php
return [
    'MyService' => \App\MyServiceProvider::class,
    'App\\MyClass' => \App\MyClass::class, 
    'MyOtherClassA' => \App\MyOtherClassA::class, 
    'any-key-name-will-do' => \App\MyOtherClassB::class, 
];
```
```php
class MyServiceProvider extends AbstractProvider
{
  /**
   * This is what happens when we call App::resolve('MyService')
   */
    public function resolve()
    {
        // Do something before the class is instantiated
        $time = time();
        $settings = Config::item('settings');
        
        // return new instance
        return App::make(MyService::class, [$time, $settings]); 
        
        // ... or make singleton and resolve dependencies
        return $this->singleton('MySingleton', App::make(App\\MyService::class, [
            App::resolve('App\\MyOtherClassA'),
            App::resolve('App\\MyOtherClassB'),
            $time,
            $settings
        ]);   
    }
    
    /**
     * Optional: Register more config if needed
     */
    public function config()
    {
        return [
            'key' => 'value'
        ];
    }
  
    /**
     * Optional: Register more bindings if needed
     */
    public function bindings()
    {
        return [
           'SomeInterface' => SomeClass::class
        ];
    }
  
    /**
     * Optional: Register more services if needed
     */
    public function providers()
    {
        return [
            'SomeService' => SomeServiceProvider:class
        ];
    }
  
    /**
     * Optional: Register event subscribers if needed
     */
    public function subscribers()
    {
        return [
            'event.name' => EventSubscriber::Class
        ];
    }
}
```

```php
$myService = App::resolve('MyService');
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

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
$result = Middleware::dispatch(function() {
    return 'the task, for example FrontController::dispatch(Router::route())';
}, [
    'App\\Middlewares\\checkPermission',
    'App\\Middlewares\\ReportAccess',
    'App\\Middlewares\\Cache' => [(1*1*10)]
]);
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Controllers
The controllers specified in the routes are instantiated through 
Provider->make() (e.g. App::make()), which will always look for a singleton 
first, then search the service container for a provider or factory or else just
create a instance and inject dependencies. Which means there is nothing to do to 
make this controller with concrete dependencies work:
```php
class MyController
{
    public function __construct(MyModelA $myModelA, MyModelB $myModelB)
    {
        $this->modelA = $myModelA;
        $this->modelB = $myModelB;
    }
}
```
In order to use interfaces, bindings have to be registered. 
See also config/bindings.php
```php
App::bind(MyModelInterface::class, MyModel::class);
```
```php
class MyController
{
    public function __construct(MyModelInterface $myModel)
    {
        $this->model = $myModel;
    }
}
```
For a more control register a factory. See also config/providers.php 
```php
App::register(MyController::class, MyControllerFactory::class);
```
```php
class MyControllerFactory extends AbstractProvider
{
    public function resolve()
    {
        return new MyController('value1', 'value2');
    }
}
```
```php
class MyController
{
    public function __construct($optionA, $optionB)
    {
        // $optionA is 'value1', $optionB is 'value2'
    }
}
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Views
```php
// The base directory to start from
View::setBase('../resources/views/');

// The theme directory in base directory, is optional and can be ingored
View::setTheme('my-theme');

// The layout file without '.php' from the base/theme directory
View::setLayout('layouts/my-layout');

// Set variables for the view
View::set('viewValue1', 'someValue1')

// By default variables are only accessible in the current view
// To share a variable $title across all layout and views
View::share('title', 'My title');  

// Render a view without layout
View::view('pages/my-view', [
    'viewValue2' => 'someValue2'  // Same as View::set()
]);

// Render a view with layout, but in case of ajax only the view
View::render('pages/my-view', [
    'viewValue2' => 'someValue2'  // Same as View::set()
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
    <?= self::view() ?>    
</body>
</html>
```

```html
<!-- resources/views/my-theme/main/my-view.php -->

<p><?= $viewValue1 ?></p>
<p><?= $viewValue2 ?></p>
```
Result:
```html
<!DOCTYPE html>
<html>
<head>
    <title>My title</title>    
</head>
<body>
    <p>someValue1</p>   
    <p>someValue2</p>   
</body>
</html>
```
Where to do these View calls? Anywhere is fine. But one place could be:
```php
class BaseController
{
    public function __construct()
    {
        View::setBase(__DIR__'/../views/');
        View::setTheme('my-theme');
        View::setLayout('layouts/my-layout');
        
        Assets::setBase(__DIR__'/../assets');
        Assets::setTheme('my-theme');
    }
}
```
then
```php
class MyController extends BaseController
{
  private $user;
  
    public function __construct(UserInterface $user)
    {
        parent::__construct();
        
        $this->user = $user;
    }
    
    public function myAction()
    {
      View::render('my-view', ['user' => $this->user->find(1)]);
  }
}
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

### Assets
```php
// The base directory to start from
Assets::setBase('../app/Pages/resources/assets');

// The theme directory in base directory, is optional and can be ingored
Assets::setTheme('my-theme');

// Directory for css (default 'css')
Assets::setCssDir('css');

// Directory for js  (default 'js')
Assets::setJsDir('js');

// Register css files
Assets::addCss(['normalize.css', 'main.css']); 
 
//Register js files with keyword
Assets::addJs(['vendor/modernizr-2.8.3.min.js'], 'top');

// Register more js files with another keyword
Assets::addJs(['plugins.js', 'main.js'], 'bottom'); 

// Js from CDN
Assets::addExternalJs(['https://code.jquery.com/jquery-3.1.0.min.js'], 'bottom');

// Add inline javascript
Assets::addInlineScripts('jQueryFallback', function () use ($view) {
    return View::render('scripts/jquery-fallback', [], true);
});
```
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


<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

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

##### List all events and subscribers
```bash
$ php minimal events
```

##### List all registered config
```bash
$ php minimal config
```

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>

---
## Components

Note about failing builds: These packages have no tests (yet?). This is a free time project and since I don't have much free time, for now the less important packages will have to do without tests.         

Minimal requires at least these packages:
- [![Build Status](https://travis-ci.org/judus/minimal-collections.svg?branch=master)](https://travis-ci.org/judus/minimal-collections)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/collections.svg)](https://packagist.org/packages/minimal/collections) 
  [judus/minimal-collections](https://github.com/judus/minimal-collections) - a simple iterator
- [![Build Status](https://travis-ci.org/judus/minimal-config.svg?branch=master)](https://travis-ci.org/judus/minimal-config)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/config.svg)](https://packagist.org/packages/minimal/config) 
  [judus/minimal-config](https://github.com/judus/minimal-config) - handles a multidimensional array
- [![Build Status](https://travis-ci.org/judus/minimal-controllers.svg?branch=master)](https://travis-ci.org/judus/minimal-controllers)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/controllers.svg)](https://packagist.org/packages/minimal/controllers)
  [judus/minimal-controllers](https://github.com/judus/minimal-controllers) - the frontcontroller
- [![Build Status](https://travis-ci.org/judus/minimal-event.svg?branch=master)](https://travis-ci.org/judus/minimal-event)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/event.svg)](https://packagist.org/packages/minimal/event) 
  [judus/minimal-event](https://github.com/judus/minimal-event) - a simple event dispatcher
- [![Build Status](https://travis-ci.org/judus/minimal-http.svg?branch=master)](https://travis-ci.org/judus/minimal-http)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/http.svg)](https://packagist.org/packages/minimal/http)
  [judus/minimal-http](https://github.com/judus/minimal-http) - request and response objects
- [![Build Status](https://travis-ci.org/judus/minimal-middlewares.svg?branch=master)](https://travis-ci.org/judus/minimal-middlewares)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/middlewares.svg)](https://packagist.org/packages/minimal/middlewares)
  [judus/minimal-middlewares](https://github.com/judus/minimal-middlewares) - a unconventional middleware implementation
- [![Build Status](https://travis-ci.org/judus/minimal-minimal.svg?branch=master)](https://travis-ci.org/judus/minimal-minimal)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/minimal.svg)](https://packagist.org/packages/minimal/minimal)
  [judus/minimal-minimal](https://github.com/judus/minimal-minimal) - the application object that binds all together
- [![Build Status](https://travis-ci.org/judus/minimal-provider.svg?branch=master)](https://travis-ci.org/judus/minimal-provider)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/provider.svg)](https://packagist.org/packages/minimal/provider)
  [judus/minimal-provider](https://github.com/judus/minimal-provider) - service provider and dependency injector
- [![Build Status](https://travis-ci.org/judus/minimal-routing.svg?branch=master)](https://travis-ci.org/judus/minimal-routing)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/routing.svg)](https://packagist.org/packages/minimal/routing)
  [judus/minimal-routing](https://github.com/judus/minimal-routing) - the router

These packages are also included but are not necessary:
- [![Build Status](https://travis-ci.org/judus/minimal-assets.svg?branch=master)](https://travis-ci.org/judus/minimal-assets)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/assets.svg)](https://packagist.org/packages/minimal/assets) 
  [judus/minimal-assets](https://github.com/judus/minimal-assets) - register css and js during runtime, dump html link and script tags
- [![Build Status](https://travis-ci.org/judus/minimal-benchmark.svg?branch=master)](https://travis-ci.org/judus/minimal-benchmark)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/benchmark.svg)](https://packagist.org/packages/minimal/benchmark) 
  [judus/minimal-benchmark](https://github.com/judus/minimal-benchmark) - this "benchmarking" class is only used in the demo
- [![Build Status](https://travis-ci.org/judus/minimal-cli.svg?branch=master)](https://travis-ci.org/judus/minimal-cli)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/cli.svg)](https://packagist.org/packages/minimal/cli) 
  [judus/minimal-cli](https://github.com/judus/minimal-cli) - a command line interface, which will be completely redone asap
- [![Build Status](https://travis-ci.org/judus/minimal-database.svg?branch=master)](https://travis-ci.org/judus/minimal-database)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/database.svg)](https://packagist.org/packages/minimal/database) 
  [judus/minimal-database](https://github.com/judus/minimal-database) - a pdo connector, a mysql query builder and a ORM
- [![Build Status](https://travis-ci.org/judus/minimal-html.svg?branch=master)](https://travis-ci.org/judus/minimal-html)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/html.svg)](https://packagist.org/packages/minimal/html) 
  [judus/minimal-html](https://github.com/judus/minimal-html) - for now just a html table class
- [![Build Status](https://travis-ci.org/judus/minimal-log.svg?branch=master)](https://travis-ci.org/judus/minimal-log)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/log.svg)](https://packagist.org/packages/minimal/log) 
  [judus/minimal-log](https://github.com/judus/minimal-log) - a simple logger
- [![Build Status](https://travis-ci.org/judus/minimal-paths.svg?branch=master)](https://travis-ci.org/judus/minimal-paths)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/paths.svg)](https://packagist.org/packages/minimal/paths) 
  [judus/minimal-paths](https://github.com/judus/minimal-paths) - might help creating paths and urls
- [![Build Status](https://travis-ci.org/judus/minimal-translation.svg?branch=master)](https://travis-ci.org/judus/minimal-translation)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/translation.svg)](https://packagist.org/packages/minimal/translation) 
  [judus/minimal-translation](https://github.com/judus/minimal-translation) - translations in a pretty printed json file
- [![Build Status](https://travis-ci.org/judus/minimal-views.svg?branch=master)](https://travis-ci.org/judus/minimal-views)
  [![Latest Version](http://img.shields.io/packagist/v/minimal/views.svg)](https://packagist.org/packages/minimal/views) 
  [judus/minimal-views](https://github.com/judus/minimal-views) - simple php views and layouts

---
#### TODOs until v1.0.0
- More tests for the core packages
- Test all the complementary libraries
- Api documentation
- Better demo application
- Update the README
- Website
---

### License

The Minimal framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

<sub>[Quickstart example](#quickstart-example) | [Routing](#routing) | [Dependency Injection](#dependency-injection) | [Providers](#providers) | [Middlewares](#middlewares) | [Controllers](#controllers) | [Views](#views) | [Assets](#assets) | [CLI](#cli)</sub>
