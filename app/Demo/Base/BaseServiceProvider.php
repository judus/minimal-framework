<?php

namespace App\Demo\Base;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class BaseServiceProvider extends AbstractProvider
{
    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }
}