<?php

namespace App\Demo;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class DemoServiceProvider extends AbstractProvider
{
    public function providers(): array
    {
        return [
            'demo.assets' => \App\Demo\Assets\AssetsServiceProvider::class,
            'demo.auth' => \App\Demo\Auth\AuthServiceProvider::class,
            'demo.base' => \App\Demo\Base\BaseServiceProvider::class,
            'demo.di' => \App\Demo\DI\DIServiceProvider::class,
            'demo.logger' => \App\Demo\Logger\LoggerServiceProvider::class,
            'demo.events' => \App\Demo\Events\EventsServiceProvider::class,
            'demo.orm' => \App\Demo\ORM\ORMServiceProvider::class,
            'demo.pages' => \App\Demo\Pages\PagesServiceProvider::class,
        ];
    }
}