<?php

namespace App\Demo\Logger;

use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Providers\AbstractProvider;

class LoggerServiceProvider extends AbstractProvider
{
    public function register()
    {
        require_once __DIR__ .'/Config/routes.php';
    }
}