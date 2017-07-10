This is a (working) proof of concept.

# Minimal PHP Framework

Minimal is a web application framework.

This is a skeleton for a new project. 
Install the framework with composer.

The framework core has its own repository:
https://github.com/judus/maduser-minimal

## Requirements

1. PHP version >= 7.0
2. composer

Optional:
npm for bower, grunt and gulp

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

$route->get('hello/(:any)/(:any)', function($firstname, $lastname) {
	return 'Hello ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
});

// $route->get() responds to GET requests
// $route->post() responds to POST requests
// $route->put() ...you get it
// $route->patch()
// $route->delete()

// (:any) match letters and integer
// (:num) match integer only
```
http://localhost/hello/julien/duseyau
-> Hello Julien Duseyau

##### Using controllers
```php
// in config/routes.php 

$route->get('hello/(:any)/(:any)', 'Acme\\Controllers\\YourController@yourMethod')
```
```php
// in app/Controllers/YourController.php

class Acme\Controllers\YourController
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
            // Log or send a access report
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
```
##### File download
```php
$route->get('download/pdf', function () use ($response) {
    $response->header('Content-Type: application/pdf');
    $response->header('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('sample.pdf');
});
```
### Middlewares

```php
// in config/routes.php

$route->get('users', [
	'controller' => 'UsersController',
	'action' => 'list',
	'middlewares' => [
		// Check if the client is authorized to access this route
		'Acme\\Middlewares\\checkPermission',
		// Send a email to the administrator
		'Acme\\Middlewares\\ReportAccess',
		// Cache for x seconds
		'Acme\\Middlewares\\Cache' => [(1*1*10)]
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
	'Acme\\Middlewares\\checkPermission',
	'Acme\\Middlewares\\ReportAccess',
	'Acme\\Middlewares\\Cache' => [(1*1*10)]
];

// The middleware controller
$middleware = new Maduser\Minimal\Core\Middleware($middlewares);

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
	'Acme\\MyClass' => Acme\MyClassProvider::class, 
	'Acme\\MyOtherClassA' => Acme\MyOtherClassAProvider::class, 
	'Acme\\MyOtherClassB' => Acme\MyOtherClassBProvider::class, 
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
		return $this->singleton('MyClass', new Acme\\MyClass(
			IOC::resolve('Acme\\MyOtherClassA'),
			IOC::resolve('Acme\\MyOtherClassB'),
			$time,
			$settings
	   ));
	}
}

// IOC::resolve('Acme\\MyOtherClassA')
// Resolves a class through a provider as defined in config/providers.php
```

### Dependency injection

Binding a interface implementation
```php
// in config/bindings.php

return [
	'Acme\\InterfaceA' => Acme\ClassA::class,
	'Acme\\InterfaceB' => Acme\ClassB::class',
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

$view = new Maduser\Minimal\Libraries\View\View();
$view->setBase('../resources/views/'); // Path from index.php
$view->setTheme('my-theme'); // Set a subdir (optional)
$view->setLayout('layouts/my-layout') // View wrapper

// Share a variable $title across all views
$view->share('title', 'My title');  

// Set variables only for this view
$view->set('viewValue1', 'someValue1')

$view->render('pages/my-view', [
	'viewValue2' => 'someValue2', // Same as $view->set()
	'viewValue3' => 'someValue3'  // Same as $view->set()
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
	<?=$this->yield()?>	
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
$assets = new Maduser\Minimal\Libraries\Assets\Assets();
$assets->setBase('../app/Pages/resources/assets'); // Set base dir of assets
$assets->setTheme('my-theme'); // Optional subdirectory
$assets->setCssDir('css'); // Directory for the css
$assets->setJsDir('js'); // Directory for the js
$assets->addCss(['normalize.css', 'main.css']); // Register css files
$assets->addJs(['vendor/modernizr-2.8.3.min.js'], 'top'); //Register js files with keyword
$assets->addJs(['plugins.js', 'main.js'], 'bottom'); // Register more js files with another keyword
$assets->addExternalJs(['https://code.jquery.com/jquery-3.1.0.min.js'], 'bottom'); // Js from CDN

/** @var Maduser\Minimal\Libraries\View\View $view */
$assets->addInlineScripts('jQueryFallback', function () use ($view) {
	return $view->render('scripts/jquery-fallback', [], true);
});
```

The Assets class injected into the View class
```html
<!-- resources/views/my-theme/layouts/my-layout.php -->
<html>
<head>
    <title><?=$title?></title>

    <?= $this->assets->getCss() ?>
    <?= $this->assets->getJs('top') ?>
</head>
<body>
    <div class="content">
        ...
    </div>

    <?= $this->assets->getExternalJs('bottom') ?>
    <?= $this->assets->getInlineScripts('jQueryFallback') ?>
    <?= $this->assets->getJs('bottom') ?>
    <?= $this->assets->getInlineScripts() ?>
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

$modules->register('your-module-dirname', [
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
| GET  | /welcome/(:any)/(:any)  | Acme\Controllers\YourController@yourMethod           |                                                                 |
| GET  | /auth/login             | Acme\Controllers\AuthController@loginForm            |                                                                 |
| POST | /auth/login             | Acme\Controllers\AuthController@login                |                                                                 |
| GET  | /auth/logout            | Acme\Controllers\AuthController@logout               |                                                                 |
| GET  | /auth/users             | Acme\Controllers\UserController@list                 | Acme\Middlewares\CheckPermission, Acme\Middlewares\ReportAccess |
| GET  | /auth/users/create      | Acme\Controllers\UserController@createForm           | Acme\Middlewares\CheckPermission, Acme\Middlewares\ReportAccess |
| GET  | /auth/users/edit/(:num) | Acme\Controllers\UserController@editForm             | Acme\Middlewares\CheckPermission, Acme\Middlewares\ReportAccess |
| GET  | /download/pdf           | <= Closure()                                         |                                                                 |
| GET  | /huge/data/table        | Acme\Controllers\YourController@timeConsumingAction  | Acme\Middlewares\Cache(10)                                      |
| GET  | /pages/(:any)           | Acme\Pages\Controllers\PagesController@getStaticPage | Acme\Middlewares\Cache(10)                                      |
| GET  | /pages/info             | Acme\Pages\Controllers\PagesController@info          | Acme\Middlewares\Cache(10)                                      |
| GET  | /assets/(:any)          | Acme\Assets\Controllers\AssetsController@getAsset    |                                                                 |
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

### Frontend tools

Install packages
```bash
$ cd resources/npm
$ npm install
```
Adjust paths.json
```json
{
  "source": "../assets", 
  "destination": "../../public/assets"
}
```
Run grunt and grunt watch
```bash
$ grunt
```
Run gulp and gulp watch (not implemented yet)
```bash
$ gulp
```
Both Grunt and Gulp will compile (sass), concat, minify, uglify, copy from source to destination

See resources/npm/grunt for Grunt setup options

See resources/npm/gulp for Gulp setup options (not implemented yet)

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
