<?php

return [
    // Core classes
    'CollectionFactory' => Maduser\Minimal\Providers\CollectionFactoryProvider::class,
    'Collection'        => Maduser\Minimal\Providers\CollectionProvider::class,
    'Config'            => Maduser\Minimal\Providers\ConfigProvider::class,
    'ControllerFactory' => Maduser\Minimal\Providers\ControllerFactoryProvider::class,
    'FrontController'   => Maduser\Minimal\Providers\FrontControllerProvider::class,
    'Middleware'        => Maduser\Minimal\Providers\MiddlewareProvider::class,
    'ModelFactory'      => Maduser\Minimal\Providers\ModelFactoryProvider::class,
    'ModuleFactory'     => Maduser\Minimal\Providers\ModuleFactoryProvider::class,
    'Module'            => Maduser\Minimal\Providers\ModuleProvider::class,
    'Presenter'         => Maduser\Minimal\Providers\PresenterProvider::class,
    'Request'           => Maduser\Minimal\Providers\RequestProvider::class,
    'Response'          => Maduser\Minimal\Providers\ResponseProvider::class,
    'Route'             => Maduser\Minimal\Providers\RouteProvider::class,
    'Router'            => Maduser\Minimal\Providers\RouterProvider::class,
    'ViewFactory'       => Maduser\Minimal\Providers\ViewFactoryProvider::class,
    'Modules'           => Maduser\Minimal\Providers\ModulesProvider::class,

    // Libraries
    'Maduser\Minimal\Libraries\Assets\Assets' =>
        Maduser\Minimal\Libraries\Assets\AssetsProvider::class,
    'Maduser\Minimal\Libraries\View\View' =>
        Maduser\Minimal\Libraries\View\ViewProvider::class,
    'Maduser\Minimal\Libraries\Content\Content' =>
        Maduser\Minimal\Libraries\Content\ContentProvider::class,
];
