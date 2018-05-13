<?php

use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\Router;

Router::group([
    'middlewares' => []
], function() {

    Router::get('events', function() {
        Event::dispatch('event.a', 'Some message');
        Event::dispatch('event.b', ['some' => 'data']);
        Event::dispatch('event.c');
    });

});
