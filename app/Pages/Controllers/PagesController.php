<?php namespace Acme\Pages\Controllers;

use Maduser\Minimal\Base\Core\Controller;
use Maduser\Minimal\Base\Interfaces\ConfigInterface;
use Maduser\Minimal\Base\Interfaces\RequestInterface;
use Maduser\Minimal\Base\Interfaces\RouterInterface;
use Maduser\Minimal\Base\Interfaces\RouteInterface;
use Maduser\Minimal\Base\Interfaces\ViewInterface;
use Maduser\Minimal\Base\Interfaces\AssetInterface;
use Maduser\Minimal\Base\Interfaces\ResponseInterface;
use Maduser\Minimal\Base\Interfaces\ModulesInterface;

/**
 * Class PagesController
 *
 * @package Acme\Pages\Controllers
 */
class PagesController extends Controller
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
     * @var AssetInterface
     */
    protected $asset;

    /**
     * @var AssetInterface
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
     * @param AssetInterface    $asset
     */
    public function __construct(
        ConfigInterface $config,
        RequestInterface $request,
        RouterInterface $router,
        ResponseInterface $response,
        ViewInterface $view,
        AssetInterface $asset,
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
        $this->asset = $asset;
        $this->modules = $modules;

        $this->view->setBase('../app/Pages/resources/views');
        $this->view->setTheme('my-theme');
        //$this->view->setDir('views');
        $this->view->setLayout('layouts/my-layout');
        $this->view->share('title', 'My title');

        $this->asset->setBase('../app/Pages/resources');
        $this->asset->setTheme('my-theme');
        $this->asset->setCssDir('assets/css');
        $this->asset->setJsDir('assets/js');
        $this->asset->addCss(['normalize.css', 'main.css']);
        $this->asset->addJs(['vendor/modernizr-2.8.3.min.js'], 'top');
        $this->asset->addJs(['plugins.js', 'js/main.js'], 'bottom');
        $this->asset->addExternalJs(['https://code.jquery.com/jquery-3.1.0.min.js'], 'bottom');
        $this->asset->addInlineScripts('jQueryFallback', function () {
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
        show($this->asset, 'Asset');
    }
}
