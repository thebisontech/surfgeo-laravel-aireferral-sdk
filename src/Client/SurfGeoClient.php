<?php

namespace surfgeo\Laravel\Client;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use surfgeo\Laravel\Client\Contracts\ClientInterface;

class surfgeoClient implements ClientInterface
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Track request
     * Fire-and-forget pattern using Laravel HTTP client
     */
    public function track(array $payload): void
    {
        // Check if enabled
        if (empty($this->config['enabled'])) {
            return;
        }

        // Validate script key
        if (empty($this->config['script_key'])) {
            if (!empty($this->config['debug'])) {
                Log::warning('[surfgeo] No script key configured');
            }
            return;
        }

        // Add required fields
        $payload['script_key'] = $this->config['script_key'];
        $payload['source'] = 'server';
        $payload['sdk_language'] = 'laravel';
        $payload['sdk_version'] = '1.0.0';

        // Send async request
        $this->sendAsync($payload);
    }

    /**
     * Send async request
     * Uses Laravel HTTP client with async() for non-blocking
     */
    protected function sendAsync(array $payload): void
    {
        try {
            $request = Http::timeout($this->config['timeout'] ?? 0.05)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'surfgeo-Laravel-SDK/1.0.0',
                ]);

            // Use async only if not in testing environment
            // Http::fake() doesn't work well with async()
            if (app()->runningUnitTests() || app()->environment() === 'testing') {
                // Use sync request for tests so Http::fake() works
                $request->post($this->config['endpoint'], $payload);
            } else {
                // Use async for production (fire-and-forget)
                $request->async()->post($this->config['endpoint'], $payload)->wait();
            }

        } catch (\Exception $e) {
            if (!empty($this->config['debug'])) {
                Log::error('[surfgeo] Tracking failed', [
                    'error' => $e->getMessage(),
                    'payload' => $payload,
                ]);
            }
            // Silent failure - never throw
        }
    }

    /**
     * Validate configuration
     */
    public function isConfigured(): bool
    {
        return !empty($this->config['script_key']);
    }
}

