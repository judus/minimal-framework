<?php

namespace App\Demo\Events;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Providers\AbstractProvider;

class EventsServiceProvider extends AbstractProvider
{
    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }

    /**
     *
     */
    public function subscribers(): array
    {
        $subscribers = require_once __DIR__ . '/Config/subscribers.php';

        foreach ($subscribers as &$subscriber) {
            $subscriber = App::make($subscriber);
        }

        return $subscribers;
    }
}