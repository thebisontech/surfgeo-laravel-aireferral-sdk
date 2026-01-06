<?php

namespace surfgeo\Laravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use surfgeo\Laravel\surfgeoServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            surfgeoServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup test routes
        $app['router']->get('/', function () {
            return 'Test response';
        });

        $app['router']->get('/health-check', function () {
            return 'OK';
        });
    }
}

