<?php
use Maduser\Minimal\Facades\App;
use Maduser\Minimal\Facades\View;
use Symfony\Component\VarDumper\VarDumper;

if ( ! function_exists('view')) {
    /**
     * Simple view renderer
     *
     * @param            $viewPath
     * @param array|null $data
     *
     * @return string
     */
    function view($viewPath, Array $data = null)
    {
        return View::render($viewPath, $data);
    }
}

if (!function_exists('run')) {
    /**
     * Execute a route from uri segments
     *
     * @param       $uri
     * @param array $options
     *
     * @return mixed
     */
    function run($uri, $options = null)
    {
        return App::run($uri, $options);
    }

}


if (!function_exists('show')) {
    /**
     * @param null $data
     * @param null $heading
     * @param bool $getContents
     *
     * @return string
     */
    function show($data = null, $heading = null, $getContents = false)
    {
        !is_null($data) OR $data = 'Hi from ' . debug_backtrace()[0]['file'] .
            ' at line ' . debug_backtrace()[0]['line'];

        $string = '<div class="debug_show">';
        $string .= $heading ? '<span>' . $heading : '';
        $string .= $heading ? '</span>' : '';
        $string .= '<pre>';
        $string .= htmlentities(print_r($data, true));
        $string .= '</pre>';
        $string .= '</div>';

        if ($getContents) {
            return $string;
        }

        echo $string;
    }
}

if (!function_exists('d')) {
    function d($data = null, $heading = null, $return = false)
    {
        $string = '';

        if (class_exists(VarDumper::class)) {
            $array = [];
            is_null($heading) || $array['heading'] = $heading;
            is_null($data) || $array['data'] = $data;
            count($array) > 0 || $array['data'] = null;
            ob_start();
            call_user_func_array('dump', $array);
            $string .= ob_get_contents();
            ob_end_clean();
        } else {
            $string .= show($data, $heading, true);
        }

        if ($return) {
            return $string;
        }

        echo $string;
    }
}

if (!function_exists('dd')) {
    function dd($data = null, $heading = null)
    {
        echo d($data, $heading, true);
        exit();
    }
}


if (!function_exists('k')) {
    function k($data = null, $heading = null, $return = false)
    {
        $string = '';

        if (class_exists(Kint::class)) {
            ob_start();
            is_null($heading) || $string .= show($heading, null, true);
            Kint::$enabled_mode = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1');
            Kint::$max_depth = 6;
            Kint_Renderer_Rich::$theme = $_SERVER['DOCUMENT_ROOT'] . '/../vendor/kint-php/kint/resources/compiled/aante-light.css';
            Kint::dump($data);
            $string .= ob_get_contents();
            ob_end_clean();
        } else {
            $string = d($data, $heading, true);
        }

        if ($return) {
            return $string;
        }

        echo $string;
    }
}

if (!function_exists('kd')) {
    function kd($data = null, $heading = null)
    {
        echo k($data, $heading, true);
        exit();
    }
}

