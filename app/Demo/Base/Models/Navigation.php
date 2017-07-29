<?php namespace Acme\Demo\Base\Models;

use Maduser\Minimal\Facades\Router;

class Navigation
{
    public function __toString()
    {
        $routes = Router::getRoutes();
        $html = '';
        /** @var \Maduser\Minimal\Routers\Route $route */
        foreach ($routes->get('GET') as $route) {
            $params = $route->getUriParameters();
            $args = [];
            foreach ($params as $param) {
                if ($param == '(:num)') {
                    $args[] = rand(1, 9);
                } else {
                    $args[] = substr(md5(microtime()), rand(0, 26), 3);
                }
            }

            $uri = call_user_func_array([$route, 'uri'], $args);
            $text = $route->getUriPattern();
            $html .= '<li><a href="' . $uri . '">' . $text . '</a></li>';
        }

        return '<ul>' . $html . '</ul>';
    }
}