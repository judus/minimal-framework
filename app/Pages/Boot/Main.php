<?php namespace Maduser\Minimal\Pages\Boot;

use Maduser\Minimal\Base\Interfaces\ModuleBootInterface;
use Maduser\Minimal\Base\Interfaces\ConfigInterface;
use Maduser\Minimal\Base\Interfaces\RequestInterface;
use Maduser\Minimal\Base\Interfaces\ResponseInterface;
use Maduser\Minimal\Base\Interfaces\RouterInterface;
use Maduser\Minimal\Base\Core\ModuleBoot;

/**
 * Class Main
 *
 * @package Maduser\Minimal\Pages\Boot
 */
class Main extends ModuleBoot implements ModuleBootInterface
{
    /**
     * Main constructor.
     *
     * @param ConfigInterface   $config
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param RouterInterface   $router
     */
    public function __construct(
        ConfigInterface $config,
        RequestInterface $request,
        ResponseInterface $response,
        RouterInterface $router
    ) {
        $this->registerConfig($config, $request, $response);
        $this->registerRoutes($config, $request, $response, $router);
    }

    /**
     * @param ConfigInterface   $config
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function registerConfig(
        ConfigInterface $config,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        // TODO: Implement registerConfig() method.
    }

    /**
     * @param ConfigInterface   $config
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param RouterInterface   $route
     */
    public function registerRoutes(
        ConfigInterface $config,
        RequestInterface $request,
        ResponseInterface $response,
        RouterInterface $route
    ) {
        $route->group([
            'namespace' => 'Maduser\\Minimal\\Pages\\Controllers\\',
            'uriPrefix' => 'module'
        ], function () use ($route) {
            /** @var \Maduser\Minimal\Base\Core\Router $route */
            $route->get('pages/index', 'PagesController@index');
            $route->get('pages/welcome', 'PagesController@welcome');
            $route->get('pages/contact', 'PagesController@contact');
            $route->get('pages/info', 'PagesController@info');
        });
    }

    /**
     *
     */
    public function getConfig()
    {
        // TODO: Implement getConfig() method.
    }

    /**
     * @return string
     */
    public function getRoutes()
    {
        // TODO: Implement getConfig() method.
    }

    /**
     *
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }
}