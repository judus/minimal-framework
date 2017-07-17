<?php namespace Acme\Pages\Controllers;

use Maduser\Minimal\Facades\App;
use Maduser\Minimal\Facades\Router;
use Maduser\Minimal\Config\ConfigInterface;
use Maduser\Minimal\Apps\FactoryInterface;
use Maduser\Minimal\Http\RequestInterface;
use Maduser\Minimal\Routers\RouterInterface;
use Maduser\Minimal\Routers\RouteInterface;
use Maduser\Minimal\Views\ViewInterface;
use Maduser\Minimal\Assets\AssetsInterface;
use Maduser\Minimal\Http\ResponseInterface;

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

    /**
     * PageController constructor.
     *
     * @param ConfigInterface   $config
     * @param RequestInterface  $request
     * @param RouterInterface   $router
     * @param ResponseInterface $response
     * @param ViewInterface     $view
     * @param AssetsInterface   $assets
     * @param FactoryInterface  $modules
     */
    public function __construct(
        ConfigInterface $config,
        RequestInterface $request,
        RouterInterface $router,
        ResponseInterface $response,
        ViewInterface $view,
        AssetsInterface $assets,
        FactoryInterface $modules
    ) {
        /** @var \Maduser\Minimal\Config\Config $config */
        $this->config = $config;
        /** @var \Maduser\Minimal\Http\Request $request */
        $this->request = $request;
        /** @var \Maduser\Minimal\Routers\Router $router */
        $this->router = $router;
        /** @var \Maduser\Minimal\Http\Response $response */
        $this->response = $response;
        /** @var \Maduser\Minimal\Views\View $view */
        $this->view = $view;
        /** @var \Maduser\Minimal\Assets\Assets $assets */
        $this->assets = $assets;
        /** @var \Maduser\Minimal\Core\Modules $modules */
        $this->modules = $modules;
        //show($this->assets, 'assests');

        // Setup views
        $this->view->setBase('../app/Pages/resources/views');
        $this->view->setTheme('my-theme');
        $this->view->setLayout('layouts/my-layout');
        $this->view->share('title', 'My title');
        $this->view->share('assets', $this->assets);

        // Setup assets
        $this->assets->setSource('../app/Pages/public/build');
        $this->assets->setBase('assets/pages/public/build');
        $this->assets->setTheme('my-theme');
        $this->assets->setCssDir('css');
        $this->assets->setJsDir('js');
        $this->assets->setVendorDir('vendor');

        // Register assets
        $this->assets->addCss([
            'main.css'
        ], 'top');

        $this->assets->addVendorJs([
            'modernizr/modernizr.min.js'
        ], 'top');

        $this->assets->addVendorJs([
            'tether/js/tether.min.js',
            'bootstrap/js/bootstrap.min.js',
            'fastclick/lib/fastclick.js'
        ], 'bottom');

        $this->assets->addJs([
            'main.min.js',
            'fallback.js'
        ], 'bottom');

        $this->assets->addExternalJs([
            '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'
        ], 'bottom');

        $this->assets->addInlineScripts('jQueryFallback', function () {
            return $this->view->render('scripts/jquery-fallback', [], true);
        });

    }

    /**
     * @param null $name
     *
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

        /*

        ob_start();
        show($this->config, 'Config');
        show($this->request, 'Request');
        show($this->router, 'Router');
        show($this->router->getRoute(), 'Route');
        show($this->modules, 'Modules');
        show($this->response, 'Response');
        show($this->view, 'View');
        show($this->assets, 'Assets');
        $contents = ob_get_contents();
        ob_end_clean();
        */
        ob_start();
        d($this->config, 'Config');
        d($this->request, 'Request');
        d($this->router, 'Router');
        d($this->router->getRoute(), 'Route');
        d($this->modules, 'Modules');
        d($this->response, 'Response');
        d($this->view, 'View');
        d($this->assets, 'Assets');
        $contents = ob_get_contents();
        ob_end_clean();

        $result = run('blabla');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        //echo $result; die();
        //show($result);die();

        return $this->view->render('pages/my-view', [
            'title' => run('welcome/john/doe'),
            'content' => $contents . $result
        ]);

    }
}