<?php namespace Acme\Controllers;

use Maduser\Minimal\Interfaces\ResponseInterface;

/**
 * Class AuthController
 *
 * @package Acme\Pages\Controllers
 */
class AuthController
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * AuthController constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        if (!session_id()) {
            session_start();
        }
    }

    /**
     * @return string
     */
    public function loginForm()
    {
        if ($this->isLoggedIn()) {
            $html = '<p>You are logged in as '
                . $_SESSION['currentUser'] .'</p>';

            $html.= '<a href="/auth/logout">Logout</a>';
            return $html;
        }

        $html = '<p>Imagine a login form and press the button:</p>';
        $html.= '<form action="/auth/login" method="post">';
        $html.= '<input type="submit" name="login" value="login">';
        $html.= '</form >';

        return $html;
    }

    /**
     *
     */
    public function login()
    {
        $_SESSION['currentUser'] = 'jondoe';
        $this->redirect();
    }

    /**
     *
     */
    public function logout()
    {
        unset($_SESSION['currentUser']);
        $this->response->redirect('/auth/login');
    }

    /**
     * @return bool
     */
    private function isLoggedIn()
    {
        if (isset($_SESSION['currentUser'])) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    private function redirect()
    {
        $redirectUrl = $_SESSION['redirectUrl'];
        unset($_SESSION['redirectUrl']);

        $this->response->redirect($redirectUrl);
    }
}