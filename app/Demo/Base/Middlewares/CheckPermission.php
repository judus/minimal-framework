<?php namespace Acme\Demo\Base\Middlewares;

use Maduser\Minimal\Middlewares\AbstractMiddleware;
use Maduser\Minimal\Middlewares\MiddlewareInterface;
use Maduser\Minimal\Http\RequestInterface;
use Maduser\Minimal\Http\ResponseInterface;

/**
 * Class CheckPermission
 *
 * @package Acme\Middlewares
 */
class CheckPermission extends AbstractMiddleware
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * CheckPermission constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->request = $request;
        $this->response = $response;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Redirect to login page if not logged in
     */
    public function before()
    {
        if (!isset($_SESSION['currentUser'])) {
            $_SESSION['redirectUrl'] = '/' . $this->request->getUriString();
            $this->response->redirect('/auth/login');
            return false;
        }
    }

    /**
     * Do nothing
     */
    public function after()
    {
    }
}