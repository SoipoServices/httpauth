<?php

namespace SoipoServices\HttpAuth\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use SoipoServices\HttpAuth\HttpAuthServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            HttpAuthServiceProvider::class,
        ];
    }
}