<?php

/** @var \Maduser\Minimal\Routing\Router $router */

use App\Demo\Events\SubscriberA;
use App\Demo\Events\SubscriberB;
use Maduser\Minimal\Framework\Facades\Event;

$router->group([
    'middlewares' => []
], function() use ($router) {

    $router->get('events', function() {
        Event::dispatch('event.a', 'Some message');
        Event::dispatch('event.b', ['some' => 'data']);
        Event::dispatch('event.c');
    });

});
