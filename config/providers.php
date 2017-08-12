<?php namespace Maduser\Minimal\Framework\Providers;

return [
    'Assets'            => AssetsProvider::class,
    'Collection'        => CollectionProvider::class,
    'CollectionFactory' => CollectionFactoryProvider::class,
    'Config'            => ConfigProvider::class,
    'ControllerFactory' => ControllerFactoryProvider::class,
    'Factory'           => FactoryProvider::class,
    'FrontController'   => FrontControllerProvider::class,
    'FormBuilder'       => FormProvider::class,
    'HtmlBuilder'       => HtmlProvider::class,
    'Middleware'        => MiddlewareProvider::class,
    'Module'            => ModuleProvider::class,
    'ModuleFactory'     => ModuleFactoryProvider::class,
    'Request'           => RequestProvider::class,
    'Response'          => ResponseProvider::class,
    'Route'             => RouteProvider::class,
    'Router'            => RouterProvider::class,
    'View'              => ViewProvider::class,
    'ViewFactory'       => ViewFactoryProvider::class,
];
