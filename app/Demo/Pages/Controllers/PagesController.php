<?php namespace App\Demo\Pages\Controllers;

use Maduser\Minimal\Assets\Contracts\AssetsInterface;
use Maduser\Minimal\Framework\Contracts\FactoryInterface;
use Maduser\Minimal\Config\Contracts\ConfigInterface;
use Maduser\Minimal\Http\Contracts\RequestInterface;
use Maduser\Minimal\Http\Contracts\ResponseInterface;
use Maduser\Minimal\Routing\Contracts\RouterInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;
use Maduser\Minimal\Views\Contracts\ViewInterface;

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
     * @var FactoryInterface
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
        /** @var \Maduser\Minimal\Routing\Router $router */
        $this->router = $router;
        /** @var \Maduser\Minimal\Http\Response $response */
        $this->response = $response;
        /** @var \Maduser\Minimal\Views\View $view */
        $this->view = $view;
        /** @var \Maduser\Minimal\Assets\Assets $assets */
        $this->assets = $assets;
        /** @var \Maduser\Minimal\Framework\Factory $modules */
        $this->modules = $modules;
    }

    private function setupAssetsAndViews()
    {
        // Setup views
        $this->view->setBase(path('modules') . 'Demo/Pages/resources/views');
        $this->view->setTheme('my-theme');
        $this->view->setLayout('layouts/my-layout');
        $this->view->share('title', 'My title');
        $this->view->share('assets', $this->assets);

        // Setup assets
        $this->assets->setSource(path('modules') . 'Demo/Pages/public/build');
        $this->assets->setBase(http().'assets/demo/pages/public/build');
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
        echo '<h3>Config</h3>';
        d($this->config);
        echo '<h3>Request</h3>';
        d($this->request);
        echo '<h3>Router</h3>';
        d($this->router);
        echo '<h3>Route</h3>';
        d($this->router->getRoute());
        echo '<h3>Modules</h3>';
        d($this->modules);
        echo '<h3>Response</h3>';
        d($this->response);
        echo '<h3>View</h3>';
        d($this->view);
        echo '<h3>Assets</h3>';
        d($this->assets);
        $contents = ob_get_contents();
        ob_end_clean();

        $result = '<hr><h3>Nested route execution with middleware</h3>';

        $result .= run('lorem');
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
        $start = time();

        $i = 0;
        while ($i < 100000000) {
            $i++;
        }

        $end = time();

        $period = $end - $start;

        $html = '<p>I have counted to ' . $i . '. It took '
            . $period . ' seconds.<br>If the response reached you faster than '
            . 'that, you received cached contents</p>'
            . '<p>Content generated at ' . date('Y-m-d h:i:sa') . '</p>'
            . '<p>Cache is valid for 5 seconds. Press ctrl+R or cmd+R to reload.</p>';

        return $html;
    }
}