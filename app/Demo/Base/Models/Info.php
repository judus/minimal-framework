<?php

namespace App\Demo\Base\Models;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Framework\Minimal;
use Maduser\Minimal\Cli\Console;
use Maduser\Minimal\Framework\Facades\IOC;

class Info
{
    private $minimal;

    private $console;

    public function __construct()
    {
        $this->console = new Console();
    }

    public function config()
    {
        $thead = [['Alias', 'Value']];
        $tbody = [];

        $items = $this->array_flat(Config::items());

        foreach ($items as $key => $value) {
            $tbody[] = [$key, $value];
        }

        ob_start();
        $this->console->table($tbody, $thead);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function routes()
    {
        /** @var \Maduser\Minimal\Collections\Contracts\CollectionInterface $routes */
        $routes = Router::getRoutes();

        $routesAll = $routes->get('ALL');

        $array = [];

        foreach ($routesAll->getArray() as $route) {

            /** @var \Maduser\Minimal\Routing\Route $route */

            $mws = $route->getMiddlewares();
            $str = '';
            foreach ($mws as $key => $mw) {

                $mw = is_array($mw) ? $key . '(' . implode($mw,
                        ', ') . ')' : $mw;
                $str .= !empty($str) ? ', ' . $mw : $mw;
            }

            $params = $route->getUriParameters();
            $args = [];
            foreach ($params as $param) {
                if ($param == '(:num)') {
                    $args[] = rand(1, 9);
                } else {
                    $args[] = substr(md5(microtime()), rand(0, 26), 3);
                }
            }

            $array[] = [
                'type' => $route->getRequestMethod(),
                'pattern' => '/' . ltrim($route->getUriPrefix() . $route->getUriPattern(), '/'),
                'action' => $route->hasClosure() ? '<= Closure()' : $route->getController() . '@' . $route->getAction(),
                'middleware' => $str
            ];

        }
        ob_start();
        $this->console->table(
            $array,
            [['Type', 'Pattern', 'Action', 'Middlewares']]
        );
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function providers()
    {
        $thead = [['Alias', 'Provider']];
        $tbody = [];

        $items = IOC::providers();

        foreach ($items->get() as $key => $value) {
            $tbody[] = [$key, $value];
        }

        ob_start();
        $this->console->table($tbody, $thead);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function bindings()
    {
        $thead = [['Alias', 'Binding']];
        $tbody = [];

        $items = IOC::bindings();

        foreach ($items->get() as $key => $value) {
            $tbody[] = [$key, $value];
        }

        ob_start();
        $this->console->table($tbody, $thead);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function events()
    {
        $thead = [['Events', 'Actions']];
        $tbody = [];

        foreach (Event::events() as $event => $subscribers)
        {
            $array = [];
            foreach ($subscribers as $subscriber)
            {
                /** @var $subscriber Subscriber */
                $actions = $subscriber->getEventActions($event);

                foreach ($actions as $action)
                {
                    $array[] = get_class($subscriber) . '::' . $action;
                }

            }

            $tbody[] = [$event, implode(', ', $array)];
        }

        ob_start();
        $this->console->table($tbody, $thead);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function array_flat($array, $prefix = '')
    {
        $result = array();

        foreach ($array as $key => $value) {
            $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

            if (is_array($value)) {
                $result = array_merge($result,
                    $this->array_flat($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

    public function __toString()
    {
        $contents = (string) new Navigation() . '</br>';
        $contents .= '</br><pre style="display: table; width: auto; margin: 0 auto;">CONFIG' . $this->config() . '</pre>';
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">ROUTES' . $this->routes() . '</pre>';
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">PROVIDERS' . $this->providers() .'</pre>';
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">BINDINGS' . $this->bindings() .'</pre>';
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">EVENTS' . $this->events() .'</pre>';

        return $contents;
    }
}