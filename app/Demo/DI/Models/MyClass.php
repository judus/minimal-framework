<?php namespace App\Demo\DI\Models;

use App\Demo\DI\Contracts\InterfaceA;
use App\Demo\DI\Contracts\InterfaceC;

class MyClass
{
    private $classA;
    private $classC;

    public function __construct(InterfaceA $classA, InterfaceC $classC)
    {
        $this->classA = $classA;
        $this->classC = $classC;
    }

    public function getClassC()
    {
        return $this->classC;
    }
}
