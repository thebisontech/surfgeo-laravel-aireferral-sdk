<?php

namespace surfgeo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void track(array $payload)
 * @method static bool isConfigured()
 */
class surfgeo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \surfgeo\Laravel\Client\Contracts\ClientInterface::class;
    }
}

