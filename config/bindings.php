<?php

return [
    // Core
    'Maduser\Minimal\Config\ConfigInterface' =>
        Maduser\Minimal\Config\Config::class,

    'Maduser\Minimal\Collections\CollectionFactoryInterface' =>
        Maduser\Minimal\Collections\CollectionFactory::class,

    'Maduser\Minimal\Collections\CollectionInterface' =>
        Maduser\Minimal\Collections\Collection::class,

    'Maduser\Minimal\Apps\ModuleFactoryInterface' =>
        Maduser\Minimal\Apps\ModuleFactory::class,

    'Maduser\Minimal\Apps\FactoryInterface' =>
        Maduser\Minimal\Apps\Factory::class,

    'Maduser\Minimal\Http\ResponseInterface' =>
        Maduser\Minimal\Http\Response::class,

    'Maduser\Minimal\Http\RequestInterface' =>
        Maduser\Minimal\Http\Request::class,

    'Maduser\Minimal\Routers\RouteInterface' =>
        Maduser\Minimal\Routers\Route::class,

    'Maduser\Minimal\Routers\RouterInterface' =>
        Maduser\Minimal\Routers\Router::class,

    // Libraries
    'Maduser\Minimal\Assets\AssetsInterface' =>
        Maduser\Minimal\Assets\Assets::class,

    'Maduser\Minimal\Views\ViewInterface' =>
        Maduser\Minimal\Views\View::class,
];
