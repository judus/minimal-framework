<?php namespace App\Demo\Base\Middlewares;

use Maduser\Minimal\Middlewares\AbstractMiddleware;
use Maduser\Minimal\Http\Contracts\RequestInterface;
use Maduser\Minimal\Http\Contracts\ResponseInterface;

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
            $_SESSION['redirectUrl'] = http() . $this->request->getUriString();
            $this->response->redirect(http() . 'auth/login');
            return false;
        }

        return true;
    }

    /**
     * Do nothing
     */
    public function after()
    {
    }
}