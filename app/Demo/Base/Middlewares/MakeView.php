<?php namespace Acme\Demo\Base\Middlewares;

use Maduser\Minimal\Assets\AssetsInterface;
use Maduser\Minimal\Config\ConfigInterface;
use Maduser\Minimal\Facades\Assets;
use Maduser\Minimal\Facades\View;
use Maduser\Minimal\Http\RequestInterface;
use Maduser\Minimal\Middlewares\AbstractMiddleware;
use Maduser\Minimal\Middlewares\MiddlewareInterface;
use Maduser\Minimal\Views\ViewInterface;

class MakeView extends AbstractMiddleware
{
    /**
     * @var
     */
    private $view;

    /**
     * Cache constructor.
     *
     * @param ViewInterface $view
     */
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }

    /**
     *
     */
    public function after()
    {
        $this->view->setBase('../app/Demo/Pages/resources/views');
        $this->view->setTheme('my-theme');
        $this->view->setLayout('layouts/my-layout');
        $this->view->share('title', 'My title');
        $this->view->share('assets', Assets::getInstance());

        Assets::setSource('../app/demo/Pages/public/build');
        Assets::setBase('assets/demo/pages/public/build');
        Assets::setTheme('my-theme');
        Assets::setCssDir('css');
        Assets::setJsDir('js');
        Assets::setVendorDir('vendor');

        // Register assets
        Assets::addCss(['main.css'], 'top');
        Assets::addVendorJs(['modernizr/modernizr.min.js'], 'top');

        Assets::addVendorJs([
            'tether/js/tether.min.js',
            'bootstrap/js/bootstrap.min.js',
            'fastclick/lib/fastclick.js'
        ], 'bottom');

        Assets::addJs(['main.min.js', 'fallback.js'], 'bottom');

        Assets::addExternalJs([
            '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'
        ], 'bottom');

        Assets::addInlineScripts('jQueryFallback', function () {
            return $this->view->render('scripts/jquery-fallback', [], true);
        });


        $view = $this->view->render('pages/my-view', [
            'title' => run('welcome/john/doe'),
            'content' => $this->getPayload()
        ]);

        $this->setPayload($view);
    }
}