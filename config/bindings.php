<?php

return [
    Maduser\Minimal\Assets\Contracts\AssetsInterface::class =>
        Maduser\Minimal\Assets\Assets::class,

    Maduser\Minimal\Collections\Contracts\CollectionInterface::class =>
        Maduser\Minimal\Collections\Collection::class,

    Maduser\Minimal\Config\Contracts\ConfigInterface::class =>
        Maduser\Minimal\Config\Config::class,

    Maduser\Minimal\Framework\Contracts\FactoryInterface::class =>
        Maduser\Minimal\Framework\Factory::class,

    Maduser\Minimal\Framework\Factories\Contracts\ModuleFactoryInterface::class =>
        Maduser\Minimal\Framework\Factories\ModuleFactory::class,

    Maduser\Minimal\Framework\Factories\Contracts\CollectionFactoryInterface::class =>
        Maduser\Minimal\Framework\Factories\CollectionFactory::class,

    Maduser\Minimal\Http\Contracts\ResponseInterface::class =>
        Maduser\Minimal\Http\Response::class,

    Maduser\Minimal\Http\Contracts\RequestInterface::class =>
        Maduser\Minimal\Http\Request::class,

    Maduser\Minimal\Routing\Contracts\RouteInterface::class =>
        Maduser\Minimal\Routing\Route::class,

    Maduser\Minimal\Routing\Contracts\RouterInterface::class =>
        Maduser\Minimal\Routing\Router::class,

    Maduser\Minimal\Views\Contracts\ViewInterface::class =>
        Maduser\Minimal\Views\View::class,
];
