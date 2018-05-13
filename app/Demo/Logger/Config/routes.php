<?php


use Maduser\Minimal\Framework\Facades\Log;
use Maduser\Minimal\Framework\Facades\Router;

Router::group([
    'middlewares' => []
], function() {

    Router::get('logger', function() {
        Log::info('Hello');
    });

});
