<?php namespace App\Demo\DI\Models;

use App\Demo\DI\Models\MyClass;
use Maduser\Minimal\Framework\Facades\Assets;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Providers\AbstractProvider;

class MyClassBFactory extends AbstractProvider
{
    public function resolve()
    {
        // Do something before the class is instantiated
        $time = time();
        //Assets::setPath();
        $settings = Config::database();

        // return new instance
        return new MyClassB($time, $settings);

        /*
        // ... or make singleton and resolve dependencies
        return $this->singleton('MyClass', new MyClass(
            IOC::resolve('App\\MyOtherClassA'),
            IOC::resolve('App\\MyOtherClassB'),
            $time,
            $settings
        ));
        */

    }
}