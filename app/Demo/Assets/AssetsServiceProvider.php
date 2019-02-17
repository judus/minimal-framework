<?php

namespace App\Demo\Assets;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class AssetsServiceProvider extends AbstractProvider
{
    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }
}