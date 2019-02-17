<?php

namespace App\Demo\Auth;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class AuthServiceProvider extends AbstractProvider
{
    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }
}