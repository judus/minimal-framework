<?php namespace Acme\Middlewares;

use Maduser\Minimal\Interfaces\MiddlewareInterface;
use Maduser\Minimal\Interfaces\RequestInterface;
use Maduser\Minimal\Interfaces\ResponseInterface;

/**
 * Class CheckPermission
 *
 * @package Acme\Middlewares
 */
class CheckPermission implements MiddlewareInterface
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

        if (!session_id()) {
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
            return $this->response->redirect('/auth/login');
        }
    }

    /**
     * Do nothing
     */
    public function after()
    {
    }
}