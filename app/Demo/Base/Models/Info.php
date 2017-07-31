<?php

namespace App\Demo\Base\Models;

use Maduser\Minimal\Apps\Minimal;
use Maduser\Minimal\Cli\Console;
use Maduser\Minimal\Loaders\IOC;

class Info
{
    public function __construct(Minimal $minimal)
    {
        $this->minimal = $minimal;
        $this->console = new Console();
    }


    public function config()
    {
        $thead = [['Alias', 'Value']];
        $tbody = [];

        $items = $this->array_flat($this->minimal->getConfig()->getItems());

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
        $router = $this->minimal->getRouter();

        /** @var Collection $routes */
        $routes = $router->getRoutes();

        $routesAll = $routes->get('ALL');

        $array = [];

        foreach ($routesAll->getArray() as $route) {

            /** @var Route $route */

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

            $uri = http() . ltrim(call_user_func_array([$route, 'uri'], $args),
                    '/');
            $text = '/' . ltrim($route->getUriPrefix() . $route->getUriPattern(),
                    '/');
            $html = '<a href="' . $uri . '">' . $text . '</a>';

            $array[] = [
                'type' => $route->getRequestMethod(),
                'pattern' => $text,
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

    public function modules()
    {
        $modules = $this->minimal->getFactory()->getModules()->getArray();

        $array = [];

        foreach ($modules as $module) {
            /** @var Module $module */
            $array[] = [
                'name' => $module->getName(),
                'path' => $module->getBasePath(),
                'config' => $module->getConfigFile(),
                'routes' => $module->getRoutesFile(),
                'providers' => $module->getProvidersFile(),
                'bindings' => $module->getBindingsFile()
            ];

        }

        ob_start();
        $this->console->table(
            $array,
            [['Name', 'BasePath', 'Config', 'Routes', 'Providers', 'Bindings']]
        );
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function providers()
    {
        $thead = [['Alias', 'Provider']];
        $tbody = [];

        $items = IOC::config('providers');

        foreach ($items as $key => $value) {
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

        $items = IOC::config('bindings');

        foreach ($items as $key => $value) {
            $tbody[] = [$key, $value];
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
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">MODULES' . $this->modules() .'</pre>';
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">PROVIDERS' . $this->providers() .'</pre>';
        $contents.= '</br><pre style="display: table; width: auto; margin: 0 auto;">BINDINGS' . $this->bindings() .'</pre>';

        return $contents;
    }
}