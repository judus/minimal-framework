<?php namespace Acme\Pages\Controllers;

use Maduser\Minimal\Base\Interfaces\ConfigInterface;
use Maduser\Minimal\Base\Interfaces\RequestInterface;
use Maduser\Minimal\Base\Interfaces\RouterInterface;
use Maduser\Minimal\Base\Interfaces\RouteInterface;
use Maduser\Minimal\Base\Interfaces\ViewInterface;
use Maduser\Minimal\Base\Interfaces\AssetsInterface;
use Maduser\Minimal\Base\Interfaces\ResponseInterface;
use Maduser\Minimal\Base\Interfaces\ModulesInterface;

/**
 * Class PagesController
 *
 * @package Acme\Pages\Controllers
 */
class PagesController
{
    /**
     * @var ConfigInterface
     */
    protected $config;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var RouteInterface
     */
    protected $route;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var ViewInterface
     */
    protected $view;
    /**
     * @var AssetsInterface
     */
    protected $assets;

    /**
     * @var ModulesInterface
     */
    protected $modules;
    protected $module;

    /**
     * PageController constructor.
     *
     * @param ConfigInterface   $config
     * @param RequestInterface  $request
     * @param RouterInterface   $router
     * @param ResponseInterface $response
     * @param ViewInterface     $view
     * @param AssetsInterface   $assets
     * @param ModulesInterface  $modules
     */
    public function __construct(
        ConfigInterface $config,
        RequestInterface $request,
        RouterInterface $router,
        ResponseInterface $response,
        ViewInterface $view,
        AssetsInterface $assets,
        ModulesInterface $modules
    ) {
        /** @var \Maduser\Minimal\Base\Core\Config $config */
        /** @var \Maduser\Minimal\Base\Core\Request $request */
        /** @var \Maduser\Minimal\Base\Core\Router $router */
        /** @var \Maduser\Minimal\Base\Core\Response $response */
        /** @var \Maduser\Minimal\Base\Core\View $view */
        /** @var \Maduser\Minimal\Base\Core\Asset $asset */
        /** @var \Maduser\Minimal\Base\Core\Modules $modules */
        $this->config = $config;
        $this->request = $request;
        $this->router = $router;
        $this->response = $response;
        $this->view = $view;
        $this->assets = $assets;
        $this->modules = $modules;

        $this->view->setBase('../app/Pages/resources/views');
        $this->view->setTheme('my-theme');
        //$this->view->setDir('views');
        $this->view->setLayout('layouts/my-layout');
        $this->view->share('title', 'My title');

        $this->assets->setBase('assets/pages/resources/assets/build');
        $this->assets->setTheme('my-theme');
        $this->assets->setCssDir('css');
        $this->assets->setJsDir('js');
        /*
        $this->assets->addCss(['bootstrap.min.css', 'bootstrap-theme.min.css']);
        */
        $this->assets->addCss(['main.min.css']);

        $this->assets->addJs(['../vendor/modernizr/modernizr.min.js'], 'top');

        $this->assets->addJs([
            '../vendor/tether/js/tether.min.js',
            '../vendor/bootstrap/js/bootstrap.min.js',
            '../vendor/fastclick/lib/fastclick.js',
            'main.js'], 'bottom');

        $this->assets->addExternalJs([
            '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'
        ], 'bottom');

        $this->assets->addInlineScripts('jQueryFallback', function () {
            return $this->view->render('scripts/jquery-fallback', [], true);
        });

    }


    /**
     * @return string
     */
    public function welcome($name = null)
    {
        $name = $name ? ' ' . ucfirst($name) : '';

        return 'Welcome' . $name . '!';
    }

    /**
     * @return string
     */
    public function contact()
    {
        return 'Imagine a contact form here';
    }

    /**
     * @param $uri
     *
     * @return string
     */
    public function getStaticPage($uri)
    {
        /*
        for ($i = 0; $i < 10000000; $i++)
        {
            $foo = 1 + 1;
        }
         */
        // replace 'sample.php' with $uri
        return $this->view->render('pages/my-view', [
            'content' => 'Would load page ' . "'" . str_replace('/', '-',
                    $uri) . "'"
        ]);
    }

    /**
     *
     */
    public function info()
    {
        show($this->config, 'Config');
        show($this->request, 'Request');
        show($this->router, 'Router');
        show($this->router->getRoute(), 'Route');
        show($this->modules, 'Modules');
        show($this->response, 'Response');
        show($this->view, 'View');
        show($this->assets, 'Assets');
    }
}
