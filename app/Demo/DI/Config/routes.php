<?php

use Maduser\Minimal\Framework\Facades\App;

/** @var \Maduser\Minimal\Routing\Router $router */

$router->group([
    'middlewares' => [
    ]
], function() use ($router) {

    App::bind([
        \App\Demo\DI\Contracts\InterfaceA::class => \App\Demo\DI\Models\ClassA::class,
        \App\Demo\DI\Contracts\InterfaceB::class => \App\Demo\DI\Models\ClassB::class,
        \App\Demo\DI\Contracts\InterfaceC::class => \App\Demo\DI\Models\ClassC::class
    ]);

    App::register([
        'App\\Demo\\DI\\Models\\MyClass' => \App\Demo\DI\Models\MyClass::class,
        'MyOtherClass' => \App\Demo\DI\Models\MyClassBFactory::class,
        'any-key-name-will-do' => \App\Demo\DI\Models\MyClassB::class,
        'MySingleton' => \App\Demo\DI\Models\MySingletonFactory::class
    ]);

    $router->get('di/bindings', function () {
        return App::bindings();
    });

    $router->get('di/providers', function () {
        return App::providers();
    });

    $router->get('di/singleton', function () {
        App::resolve('MySingleton')->setTime(123);

        return
            "<pre>" .
            "App::resolve('MySingleton')->setTime(123)\n" .
            "App::resolve('MySingleton')->getTime(123) => " .
            App::resolve('MySingleton')->getTime() . "\n" .
            "App::singleton('MySingleton')->getTime(123) => " .
            App::singleton('MySingleton')->getTime() . "\n" .
            "</pre>";
    });

    $router->get('di/make', function () {
        return App::make(\App\Demo\DI\Models\MyClass::class);
    });

    $router->get('di/resolve/my-class', function () {
        return App::resolve('App\\Demo\\DI\\Models\\MyClass');
    });

    $router->get('di/resolve/my-other-class', function () {
        return App::resolve('MyOtherClass');
    });

    $router->get('di/resolve/any-key-name-will-do', function () {
        return App::resolve('any-key-name-will-do');
    });

});
