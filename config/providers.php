<?php namespace Maduser\Minimal\Framework\Providers;

return [
    'Assets'            => AssetsProvider::class,
    'Collection'        => CollectionProvider::class,
    'Config'            => ConfigProvider::class,
    'ControllerFactory' => ControllerFactoryProvider::class,
    'Event'             => EventProvider::class,
    'FormBuilder'       => FormProvider::class,
    'FrontController'   => FrontControllerProvider::class,
    'HtmlBuilder'       => HtmlProvider::class,
    'Log'               => LoggerProvider::class,
    'Middleware'        => MiddlewareProvider::class,
    'Module'            => ModuleProvider::class,
    'Modules'           => ModulesProvider::class,
    'Request'           => RequestProvider::class,
    'Response'          => ResponseProvider::class,
    'Route'             => RouteProvider::class,
    'Router'            => RouterProvider::class,
    'View'              => ViewProvider::class,
];
