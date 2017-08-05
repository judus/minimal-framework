<?php namespace App\Demo\Auth\Controllers;

use Maduser\Minimal\Http\Contracts\ResponseInterface;

/**
 * Class AuthController
 *
 * @package App\Demo\Auth\Controllers
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

        if (session_status() == PHP_SESSION_NONE) {
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

            $html.= '<a href="' . http() . 'auth/logout">Logout</a>';
            return $html;
        }

        $html = '<p>Imagine a login form and press the button:</p>';
        $html.= '<form action="' . http() . 'auth/login" method="post">';
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
        $this->response->redirect(http() . 'auth/login');
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
        if (isset($_SESSION['redirectUrl'])) {
            $redirectUrl = $_SESSION['redirectUrl'];
            unset($_SESSION['redirectUrl']);
            $this->response->redirect($redirectUrl);
        } else {
            $this->response->redirect(http() . 'auth/login');
        }
    }
}