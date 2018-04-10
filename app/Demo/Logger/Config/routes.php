<?php

/** @var \Maduser\Minimal\Routing\Router $router */

use Maduser\Minimal\Framework\Events\Subscribers\SystemLog;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\Log;

$router->group([
    'middlewares' => []
], function() use ($router) {

    $router->get('logger', function() {
        Log::info('Hello');
    });

});
