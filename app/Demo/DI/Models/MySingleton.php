<?php

namespace App\Demo\DI\Models;

use App\Demo\DI\Contracts\InterfaceA;
use App\Demo\DI\Contracts\InterfaceB;

class MySingleton
{
    private $classA;
    private $classB;
    private $time;
    private $settings;

    public function __construct(
        InterfaceA $classA,
        InterfaceB $classB,
        $time,
        $settings
    ) {
        $this->classA = $classA;
        $this->classB = $classB;
        $this->time = $time;
        $this->settings = $settings;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function getTime()
    {
        return $this->time;
    }
}