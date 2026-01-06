<?php

namespace surfgeo\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use surfgeo\Laravel\Jobs\TrackRequest;
use surfgeo\Laravel\Http\PayloadBuilder;

class TrackAIBotsQueue
{
    protected PayloadBuilder $builder;

    public function __construct(PayloadBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            $payload = $this->builder->build($request, $response);
            TrackRequest::dispatch($payload);
        }

        return $response;
    }

    /**
     * Check if request should be tracked
     */
    protected function shouldTrack(Request $request): bool
    {
        // Check if enabled
        if (!config('surfgeo.enabled')) {
            return false;
        }

        // Check environment
        if (in_array(app()->environment(), config('surfgeo.excluded_environments', []))) {
            return false;
        }

        // Check path exclusions
        $path = $request->getPathInfo(); // Get path with leading slash
        $excludedPaths = config('surfgeo.excluded_paths', []);
        
        foreach ($excludedPaths as $pattern) {
            // Check if pattern is a simple string (no regex special chars)
            $isRegex = preg_match('/[.*+?^${}\[\]|\\\\]/', $pattern);
            
            if (!$isRegex) {
                // Simple string match for literal paths
                if ($path === $pattern || strpos($path, $pattern) === 0) {
                    return false;
                }
            } else {
                // Use regex for patterns that contain regex special characters
                if (preg_match('#' . $pattern . '#', $path)) {
                    return false;
                }
            }
        }

        return true;
    }
}

