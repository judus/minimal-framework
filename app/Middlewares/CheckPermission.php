<?php namespace Acme\Middlewares;

use Maduser\Minimal\Base\Interfaces\RequestInterface;
use Maduser\Minimal\Base\Interfaces\ResponseInterface;
use Maduser\Minimal\Base\Interfaces\RouteInterface;
use Maduser\Minimal\Base\Middlewares\Middleware;

class CheckPermission extends Middleware
{
    private $request;
    private $response;
    private $route;

    // Inject what you want
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        RouteInterface $route
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
    }

    // Executed before MVC dispatch
    public function before() {
        // If not authorised...

        // ... send appropriate response ...
        $this->response->addHeader();
        $this->response->setContent();
        $this->response->send()->exit();

        // ... or redirect to login page
        $this->response->redirect('login');

        // ... or set error and cancel dispatch
        $this->request->setError();
        return false;
    }

    // Executed after MVC dispatch
    public function after() {
        // Log or send message
        // Do some clean up
    }
}