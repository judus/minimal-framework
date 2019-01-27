<?php

use Maduser\Minimal\Framework\Facades\Router;

Router::get('/', function () {
    return 'Hello from Minimal!';
});

Router::get('phpinfo', function () {
    phpinfo();
});