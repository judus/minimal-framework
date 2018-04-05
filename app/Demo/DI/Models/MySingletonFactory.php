<?php namespace App\Demo\DI\Models;

use App\Demo\DI\Models\MyClass;
use Maduser\Minimal\Framework\Facades\Assets;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Providers\AbstractProvider;

class MySingletonFactory extends AbstractProvider
{
    public function resolve()
    {
        // Do something before the class is instantiated
        $time = time();
        //Assets::setPath();
        $settings = Config::database();

        // make singleton and resolve dependencies
        return $this->singleton('MySingleton', new MySingleton(
            IOC::resolve(ClassA::class),
            IOC::resolve(ClassB::class),
            $time,
            $settings
        ));

    }
}