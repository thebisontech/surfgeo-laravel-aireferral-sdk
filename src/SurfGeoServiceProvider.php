<?php

namespace surfgeo\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use surfgeo\Laravel\Client\surfgeoClient;
use surfgeo\Laravel\Client\Contracts\ClientInterface;
use surfgeo\Laravel\Commands\InstallCommand;
use surfgeo\Laravel\Commands\TestCommand;

class surfgeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/config/surfgeo.php',
            'surfgeo'
        );

        // Bind client to container as singleton
        $this->app->singleton(ClientInterface::class, function ($app) {
            return new surfgeoClient([
                'script_key' => config('surfgeo.script_key'),
                'endpoint' => config('surfgeo.endpoint'),
                'timeout' => config('surfgeo.timeout'),
                'debug' => config('surfgeo.debug'),
                'enabled' => config('surfgeo.enabled'),
            ]);
        });

        // Bind concrete class as alias
        $this->app->alias(ClientInterface::class, surfgeoClient::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/config/surfgeo.php' => config_path('surfgeo.php'),
        ], 'surfgeo-config');

        // Register middleware
        $this->registerMiddleware();

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                TestCommand::class,
            ]);
        }
    }

    /**
     * Register middleware based on config
     */
    protected function registerMiddleware(): void
    {
        $kernel = $this->app->make(Kernel::class);

        // Use queue-based middleware if async is enabled
        if (config('surfgeo.async', false)) {
            $kernel->pushMiddleware(\surfgeo\Laravel\Middleware\TrackAIBotsQueue::class);
        } else {
            $kernel->pushMiddleware(\surfgeo\Laravel\Middleware\TrackAIBots::class);
        }
    }
}

