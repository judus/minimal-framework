<?php namespace App\Demo\DI\Models;

use App\Demo\DI\Contracts\InterfaceB;
use App\Demo\DI\Contracts\InterfaceC;

class ClassC implements InterfaceC
{
    private $classB;

    public function __construct(InterfaceB $classB)
    {
        $this->classB = $classB;
    }
}

