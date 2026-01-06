# surfgeo Laravel SDK

Track AI bot traffic in your Laravel applications.

## Installation

```bash
composer require surfgeo/laravel-aireferral-sdk
```

## Quick Start

### 1. Publish Configuration

```bash
php artisan vendor:publish --tag=surfgeo-config
```

Or use the install command:

```bash
php artisan surfgeo:install
```

### 2. Configure Environment

Add to your `.env` file:

```env
SURFGEO_SCRIPT_KEY=sk_your_key_here
SURFGEO_ENABLED=true
```

### 3. Test Connection

```bash
php artisan surfgeo:test
```

## Configuration

Full configuration options in `config/surfgeo.php`:

```php
return [
    'script_key' => env('SURFGEO_SCRIPT_KEY', ''),
    'endpoint' => env('SURFGEO_ENDPOINT', 'https://api.surfgeo.com/api/track'),
    'enabled' => env('SURFGEO_ENABLED', true),
    'timeout' => env('SURFGEO_TIMEOUT', 0.05),
    'debug' => env('SURFGEO_DEBUG', false),
    'async' => env('SURFGEO_ASYNC', false),
    'excluded_paths' => ['/health-check', '/metrics'],
    'excluded_environments' => ['local', 'testing'],
];
```

## Usage

### Automatic Tracking

The middleware automatically tracks all requests. No additional code needed.

### Custom Tracking

Use the facade to track custom events:

```php
use surfgeo\Laravel\Facades\surfgeo;

surfgeo::track([
    'timestamp' => time(),
    'path' => '/custom-event',
    'method' => 'POST',
    'user_agent' => request()->userAgent(),
]);
```

## Features

- ✅ Auto-discovered service provider
- ✅ Laravel HTTP client integration
- ✅ Queue-based tracking (optional)
- ✅ Artisan commands (install, test)
- ✅ Facade for convenience
- ✅ Path and environment exclusions
- ✅ Debug logging

## License

MIT
