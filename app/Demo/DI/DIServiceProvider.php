<?php

namespace App\Demo\DI;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class DIServiceProvider extends AbstractProvider
{
    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }
}