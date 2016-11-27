# Minimal PHP Framework

Minimal is a web application framework.

## Requirements

1. PHP version 7.*
2. composer

## Install
```
composer create-project minimal/framework
```

## Usage

### Routing
See config/routes.php

##### Direct output:
```
// in config/routes.php

$route->get('hello/(:any)/(:any)', function($firstname, $lastname) {
	return 'Hello ' . ucfirst($name) . ' ' . ucfirst($lastname);
});

// $route->get() responds to GET requests
// $route->post() responds to POST requests
// $route->put() ...you get it
// $route->patch()
// $route->delete()
```
http://localhost/hello/julien/duseyau
-> Hello Julien Duseyau

##### Using controllers
```
// in config/routes.php 
$route->get('hello/(:any)', 'Acme\\Controllers\\YourController@yourmethod)

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


### Providers
See config/providers.php

```
// in config/providers.php
return [
	'Acme\\MyClass' => Acme\MyClassProvider::class, 
	'Acme\\MyOtherClassA' => Acme\MyClassProvider::class, 
	'Acme\\MyOtherClassB' => Acme\MyClassProvider::class, 
]

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

#### Dependency Injection
See config/bindings.php

Binding a interface implementation to a controller
```
// in config/bindings.php
return [
	'Acme\\InterfaceA' => Acme\ClassA::class,
	'Acme\\InterfaceB' => Acme\ClassB::class',
]

// in a controller
...
	public function __construct(InterfaceA $classA, InterfaceB $classB) {
		...
	}
...
```

#### Views
```
$view = new View();
$view->setPath('../resources/views/'); // Path from index.php
$view->setTheme('my-theme'); // Set a subdir (optional)


$view->render('main/my-view', [
	'viewValue1 => 'SomeValue1',
	'viewValue2 => 'SomeValue2'
]);

// resources/views/my-theme/main/my-view.php
...
<p><?= $viewValue1 ?></p>
<p><?= $viewValue2 ?></p>
...
```
```
// You could inject the View class in a controller
...
	public function __construct(ViewInterface $view)
	{
		$this->view = $view;		
		$this->view->setBaseDir('../resources/views');
	}

	public function someMethod()
	{
		return $this->view->render('my-view');
	}
...
```
#### Assets
Not implemented yet.

```
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

#### Modular structure
See config/modules.php and example module in framework/app/Pages.

1. Create a folder <your-module> in the framework/app directory 
2. Copy & Paste framework/config to framework/app/<your-module>
3. Copy & Paste framework/resources to framework/app/<your-module>
4. Register the new module in framework/config/modules.php: 


```
// in framework/config/modules.php

$modules->register('your-module', [
	// optional config array
	'path' => 'app/YourModule',
	'routesFile' => 'app/YourModule/Http/routes.php,
	...
]);
```

### License

The Minimal framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)