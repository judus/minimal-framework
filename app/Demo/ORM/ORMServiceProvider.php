<?php

namespace App\Demo\ORM;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class ORMServiceProvider extends AbstractProvider
{
    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }
}