<?php namespace App\Demo\DI\Models;

class MyClassB
{
    private $time;
    private $settings;

    public function __construct($time, $settings)
    {
        $this->time = $time;
        $this->settings = $settings;
    }

}
