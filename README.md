# Minimal PHP Framework

Minimal is a web application framework.

This repo contains only the required directories for a new project. Composer will install the framework core.

https://github.com/judus/maduser-minimal

## Requirements

1. PHP version 7.*
2. composer

## Install
```bash
$ composer create-project minimal/framework
```

## Usage

### Routing
See also config/routes.php

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
// in framework/app/Controllers/YourController.php

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
        'middleware' => [
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
    $response->setContent(readfile('original.pdf'));
    $response->send();
});
```

##### Route cache
Not implemented yet
```php
$route->get('huge/data/table', [
    // keep in cache for day: (60*60*24)
    // keep in cache forever: -1
    // disable cache: 0 or null
    'cache' => (60 * 60 * 24),
    'namespace' => 'Acme\\Controllers',
    'controller' => 'UsersController',
    'action' => 'timeConsumingAction'
]);
```
### Middleware
Not implemented yet

### Providers
See config/providers.php

```php
// in config/providers.php

return [
	'Acme\\MyClass' => Acme\MyClassProvider::class, 
	'Acme\\MyOtherClassA' => Acme\MyClassProvider::class, 
	'Acme\\MyOtherClassB' => Acme\MyClassProvider::class, 
];
```

```php
// in app/MyClassProvider.php

class MyClassProvider extends Provider
{
	public function resolve()
	{
		// Do something before the class is instanciated
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
See config/bindings.php

Binding a interface implementation to a controller
```php
// in config/bindings.php

return [
	'Acme\\InterfaceA' => Acme\ClassA::class,
	'Acme\\InterfaceB' => Acme\ClassB::class',
];
```
```php
// in a controller

class MyController
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
$view->setPath('../resources/views/'); // Path from index.php
$view->setTheme('my-theme'); // Set a subdir (optional)
$view->setLayout('layouts/my-layout') // View wrapper
$view->share('title', 'My title'); // Share a value across all views 

// The values are only available in this view
$view->render('pages/my-view', [
	'viewValue1' => 'SomeValue1',
	'viewValue2' => 'SomeValue2'
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
// You also could inject the View class in a controller

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

#### Presenters
Partially implemented.


### Assets
Partially implemented.

```php
Assets::setCssPath('/assets/css');
Assets::setJsPath('/assets/js');
Assets::addCss('bootstrap.css', 'my-theme.css');
Assets::addCss('IE.css');
Assets::addJs('bootstrap.js', 'my-script.js');

Assets::getCss();
// <link rel="stylesheet" href="/assets/css/bootstrap.css">
// <link rel="stylesheet" href="/assets/css/my-theme.css">
// <link rel="stylesheet" href="/assets/css/IE.css">

Assets::getJs()
// <script src="/assets/bootstrap.js"></script>
// <script src="/assets/my-script.js"></script>
```

### Modular structure
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
Partially implemented

   ---
#### TODOs
- Route caching
- Route model/viewModel biding
- Middleware
- Presenters
- Assets class
- Configure directories and tasks for npm, bower, grunt and/or gulp
- Demos
- Unit tests
- Documentation

---

### About

It was too cold for outdoor activities. 


### License

The Minimal framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
