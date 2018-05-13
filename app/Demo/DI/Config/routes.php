<?php

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Router;

/** @var \Maduser\Minimal\Routing\Router $router */

Router::group([
    'middlewares' => [
    ]
], function() {

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

    Router::get('di/bindings', function () {
        return App::bindings();
    });

    Router::get('di/providers', function () {
        return App::providers();
    });

    Router::get('di/singleton', function () {
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

    Router::get('di/make', function () {
        return App::make(\App\Demo\DI\Models\MyClass::class);
    });

    Router::get('di/resolve/my-class', function () {
        return App::resolve('App\\Demo\\DI\\Models\\MyClass');
    });

    Router::get('di/resolve/my-other-class', function () {
        return App::resolve('MyOtherClass');
    });

    Router::get('di/resolve/any-key-name-will-do', function () {
        return App::resolve('any-key-name-will-do');
    });

});
