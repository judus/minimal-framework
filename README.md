# Minimal PHP Framework

Minimal is a web application framework.

This repo contains only the required directories for a new project. Composer will install the framework core.

https://github.com/judus/maduser-minimal

## Requirements

1. PHP version 7
2. composer

Optional

3. npm for bower, grunt and gulp

## Install
```bash
$ composer create-project minimal/framework
```

## Usage

[Routing](#routing) | [Middlewares](#middlewares) | [Providers](#providers) | [Dependency Injection](#dependency-injection) | [Views](#views) | [Assets](#assets) | [Modules](#modules)

### Routing

##### Direct output:
```php
// in config/routes.php

$route->get('hello/(:any)/(:any)', function($firstname, $lastname) {
	return 'Hello ' . ucfirst($name) . ' ' . ucfirst($lastname);
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

$route->get('hello/(:any)/(:any)', 'Acme\\Controllers\\YourController@yourmethod')
```
```php
// in app/Controllers/YourController.php

class Acme\Controllers\YourController
{
	public function yourmethod($name, $lastname)
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
    // Define the class namespace for all routes in this group
    // Will be prefixed to the controllers
    'namespace' => 'Acme\\Controllers\\'
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
            'Acme\\Middlewares\\checkPermission',
            // Send a email to the administrator
            'Acme\\Middlewares\\ReportAccess',
        ]
    ], function () use ($route) {

        $route->get('users', [
            'controller' => 'UsersController',
            'action' => 'listUsers' // Show a list of users
        ]); 
        // Uri is: 'auth/users'
        // Controller is 'Acme\\Controllers\UsersController

        $route->get('users/create', [
            'controller' => 'UsersController',
            'action' => 'create' // Show a empty user form
        ]);
        // Uri is: 'auth/users/create'
        // Controller is 'Acme\\Controllers\UsersController
	
	// etc...
	});
});
```
##### File download
```php
$route->get('download/pdf', function () use ($response) {
    $response->addHeader('Content-Type: application/pdf');
    $response->addHeader('Content-Disposition: attachment; filename="downloaded.pdf"');
    readfile('original.pdf');
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
	    
    // Inject what you want
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        RouteInterface $route
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
    }

	// Executed before task
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

	// Executed before task
	public function before() {
		// return cached contents
	}
	
	// Executed after task
	public function after() {
		// delete old cache
		// create new cache
	}
}
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
```

### Views
```php
// anywhere in your code

$view = new Maduser\Minimal\Base\Core\View();
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
$assets = new Maduser\Minimal\Base\Core\Asset();
$assets->setBase('../app/Pages/resources/assets'); // Set base dir of assets
$assets->setTheme('my-theme'); // Optional subdirectory
$assets->setCssDir('css'); // Directory for the css
$assets->setJsDir('js'); // Directory for the js
$assets->addCss(['normalize.css', 'main.css']); // Register css files
$assets->addJs(['vendor/modernizr-2.8.3.min.js'], 'top'); //Register js files with keyword
$assets->addJs(['plugins.js', 'main.js'], 'bottom'); // Register more js files with another keyword
$assets->addExternalJs(['https://code.jquery.com/jquery-3.1.0.min.js'], 'bottom'); // Js from CDN

/** @var Maduser\Minimal\Base\Core\View $view */
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
2. Copy & Paste framework/config and  framework/resources to framework/app/your-module
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
- Route model/view binding
- ViewModel, Presenter
- Unit tests
- Api documentation

---

### About

It was too cold for outdoor activities. 


### License

The Minimal framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
