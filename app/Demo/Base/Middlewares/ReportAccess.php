<?php namespace App\Demo\Base\Middlewares;

use Maduser\Minimal\Middlewares\AbstractMiddleware;
use Maduser\Minimal\Middlewares\MiddlewareInterface;


/**
 * Class ReportAccess
 *
 * @package Maduser\Minimal\Base\Middlewares
 */
class ReportAccess extends AbstractMiddleware
{
    /**
     * @var
     */
    private $foo;

    /**
     * @var
     */
    private $bar;

     /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param mixed $foo
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param mixed $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * ReportAccess constructor.
     *
     * @param $foo
     * @param $bar
     */
    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     *
     */
    public function before()
    {
        // TODO: Implement before() method.
    }

    /**
     *
     */
    public function after()
    {
        // TODO: Implement after() method.
    }
}