<?php

return [
    // Core classes
    'CollectionFactory' => Maduser\Minimal\Collections\CollectionFactoryProvider::class,
    'Collection'        => Maduser\Minimal\Collections\CollectionProvider::class,
    'Config'            => Maduser\Minimal\Config\ConfigProvider::class,
    'ControllerFactory' => Maduser\Minimal\Controllers\ControllerFactoryProvider::class,
    'Factory'           => Maduser\Minimal\Apps\FactoryProvider::class,
    'FrontController'   => Maduser\Minimal\Controllers\FrontControllerProvider::class,
    'Middleware'        => Maduser\Minimal\Middlewares\MiddlewareProvider::class,
    'ModelFactory'      => Maduser\Minimal\Models\ModelFactoryProvider::class,
    'ModuleFactory'     => Maduser\Minimal\Apps\ModuleFactoryProvider::class,
    'Module'            => Maduser\Minimal\Apps\ModuleProvider::class,
    'Request'           => Maduser\Minimal\Http\RequestProvider::class,
    'Response'          => Maduser\Minimal\Http\ResponseProvider::class,
    'Route'             => Maduser\Minimal\Routers\RouteProvider::class,
    'Router'            => Maduser\Minimal\Routers\RouterProvider::class,
    'ViewFactory'       => Maduser\Minimal\Views\ViewFactoryProvider::class,

    // Libraries
    'Maduser\Minimal\Libraries\Assets\Assets' =>
        Maduser\Minimal\Assets\AssetsProvider::class,
    'Maduser\Minimal\Libraries\View\View' =>
        Maduser\Minimal\Views\ViewProvider::class
];
