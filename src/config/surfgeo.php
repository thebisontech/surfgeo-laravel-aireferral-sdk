<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Script Key
    |--------------------------------------------------------------------------
    |
    | Your surfgeo script key from the dashboard
    |
    */

    'script_key' => env('SURFGEO_SCRIPT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Tracking Endpoint
    |--------------------------------------------------------------------------
    |
    | The endpoint where tracking data is sent
    |
    */

    'endpoint' => env('SURFGEO_ENDPOINT', 'https://api.surfgeo.com/api/track'),

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable tracking
    |
    */

    'enabled' => env('SURFGEO_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Request timeout in seconds
    |
    */

    'timeout' => env('SURFGEO_TIMEOUT', 0.05), // 50ms

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Log tracking errors to Laravel log
    |
    */

    'debug' => env('SURFGEO_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Async Tracking
    |--------------------------------------------------------------------------
    |
    | Use Laravel queues for tracking (requires queue configuration)
    |
    */

    'async' => env('SURFGEO_ASYNC', false),

    /*
    |--------------------------------------------------------------------------
    | Queue Connection
    |--------------------------------------------------------------------------
    |
    | Which queue connection to use for async tracking
    |
    */

    'queue_connection' => env('SURFGEO_QUEUE_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Queue Name
    |--------------------------------------------------------------------------
    |
    | Which queue to dispatch tracking jobs to
    |
    */

    'queue' => env('SURFGEO_QUEUE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Excluded Paths
    |--------------------------------------------------------------------------
    |
    | Paths to exclude from tracking (regex patterns supported)
    |
    */

    'excluded_paths' => [
        '/health-check',
        '/metrics',
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Environments
    |--------------------------------------------------------------------------
    |
    | Environments where tracking should be disabled
    |
    */

    'excluded_environments' => [
        'local',
        'testing',
    ],

];

