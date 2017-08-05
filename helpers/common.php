<?php

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Path;
use Maduser\Minimal\Framework\Facades\View;
use Maduser\Minimal\Translation\Translator;
use Symfony\Component\VarDumper\VarDumper;


if (!function_exists('view')) {
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


if (!function_exists('path')) {
    /**
     * @param null $item
     *
     * @return string
     */
    function path($item = null, $root = true)
    {
        return Path::path($item, $root);
    }
}

if (!function_exists('http')) {
    /**
     * @param null $item
     *
     * @return string
     */
    function http($item = null)
    {
        return Path::http($item);
    }
}


if (!function_exists('__')) {
    /**
     * @param      $str
     * @param null $lang
     *
     * @return mixed
     */
    function __($str, $lang = null)
    {
        Translator::setFilePath(path('translations'));

        return Translator::get($str, $lang);
    }
}


if (!function_exists('lorem')) {
    /**
     * @return string
     */
    function lorem()
    {
        return 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed ' .
            'diam nonumy eirmod tempor invidunt ut labore et dolore magna ' .
            'aliquyam erat, sed diam voluptua. At vero eos et accusam et justo ' .
            'duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata ' .
            'sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, ' .
            'consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ' .
            'ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero ' .
            'eos et accusam et justo duo dolores et ea rebum. Stet clita kasd ' .
            'gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
    }
}
