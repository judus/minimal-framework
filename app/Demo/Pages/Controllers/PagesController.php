<?php namespace Acme\Demo\Pages\Controllers;

use Maduser\Minimal\Facades\App;
use Maduser\Minimal\Facades\Config;
use Maduser\Minimal\Facades\Request;
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
    }

    private function setupAssetsAndViews()
    {
        // Setup views
        $this->view->setBase('../app/Demo/Pages/resources/views');
        $this->view->setTheme('my-theme');
        $this->view->setLayout('layouts/my-layout');
        $this->view->share('title', 'My title');
        $this->view->share('assets', $this->assets);

        // Setup assets
        $this->assets->setSource('../app/Demo/Pages/public/build');
        $this->assets->setBase('assets/demo/pages/public/build');
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
        $this->setupAssetsAndViews();

        return $this->view->render('pages/my-view', [
            'content' => 'Would load partial view for page ' . "'" . str_replace('/', '-',
                    $uri) . "'"
        ]);
    }

    /**
     *
     */
    public function info()
    {
        $this->setupAssetsAndViews();

        ob_start();
        d($this->config, 'Config: $this->config');
        d($this->request, 'Request');
        d($this->router, 'Router');
        d($this->router->getRoute(), 'Route');
        d($this->modules, 'Modules');
        d($this->response, 'Response');
        d($this->view, 'View');
        d($this->assets, 'Assets');
        $contents = ob_get_contents();
        ob_end_clean();

        $result = '';
        $result  = run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');

        return $this->view->render('pages/my-view', [
            'content' => $contents . $result
        ]);

    }

    public function frontController()
    {
        $html = $this->timeConsumingAction();

        ob_start();
        d($this->config, 'Config: $this->config');
        d($this->request, 'Request');
        d($this->router, 'Router');
        d($this->router->getRoute(), 'Route');
        d($this->modules, 'Modules');
        d($this->response, 'Response');
        d($this->view, 'View');
        d($this->assets, 'Assets');
        $contents = ob_get_contents();
        ob_end_clean();

        $result = run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');
        $result .= run('lorem');

        return $html . $contents . $result;
    }

    public function timeConsumingAction()
    {
        $countTo = 1000000000;

        $start = time();
        for ($i = 0; $i < $countTo; $i++) {
            $i;
        }
        $end = time();

        $period = $end - $start;

        $html = '<p>I have counted to ' . $countTo . '. It took '
            . $period . ' seconds.<br>If the response reached you faster than '
            . 'that, you received cached contents</p>'
            . '<p>Content generated at ' . date('Y-m-d h:i:sa') . '</p>'
            . '<p>Cache is valid for 5 seconds. Press ctrl+R or cmd+R to reload.</p>';

        return $html;
    }
}