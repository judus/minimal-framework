<?php

return [
    Maduser\Minimal\Assets\Contracts\AssetsInterface::class =>
        Maduser\Minimal\Assets\Assets::class,

    Maduser\Minimal\Collections\Contracts\CollectionInterface::class =>
        Maduser\Minimal\Collections\Collection::class,

    Maduser\Minimal\Config\Contracts\ConfigInterface::class =>
        Maduser\Minimal\Config\Config::class,

    Maduser\Minimal\Controllers\Factories\Contracts\ControllerFactoryInterface::class =>
        Maduser\Minimal\Controllers\Factories\ControllerFactory::class,

    Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface::class =>
        Maduser\Minimal\Controllers\Factories\ModelFactory::class,

    Maduser\Minimal\Modules\Contracts\ModulesInterface::class =>
        Maduser\Minimal\Modules\Modules::class,

    Maduser\Minimal\Http\Contracts\ResponseInterface::class =>
        Maduser\Minimal\Http\Response::class,

    Maduser\Minimal\Http\Contracts\RequestInterface::class =>
        Maduser\Minimal\Http\Request::class,

    Maduser\Minimal\Provider\Contracts\ProviderInterface::class =>
        Maduser\Minimal\Provider\Provider::class,

    Maduser\Minimal\Routing\Contracts\RouteInterface::class =>
        Maduser\Minimal\Routing\Route::class,

    Maduser\Minimal\Routing\Contracts\RouterInterface::class =>
        Maduser\Minimal\Routing\Router::class,

    Maduser\Minimal\Views\Contracts\ViewInterface::class =>
        Maduser\Minimal\Views\View::class,
];
