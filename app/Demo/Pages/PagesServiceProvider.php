<?php

namespace App\Demo\Pages;

use Maduser\Minimal\Framework\Providers\AbstractProvider;

class PagesServiceProvider extends AbstractProvider
{
    public function bindings(): array
    {
        return require_once __DIR__ . '/Config/bindings.php';
    }

    public function providers(): array
    {
        return require_once __DIR__ . '/Config/providers.php';
    }

    public function config(): array
    {
        return require_once __DIR__ . '/Config/config.php';
    }

    public function routes()
    {
        require_once __DIR__ . '/Config/routes.php';
    }
}