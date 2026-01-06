<?php

namespace surfgeo\Laravel\Client\Contracts;

interface ClientInterface
{
    /**
     * Track a request (fire-and-forget)
     *
     * @param array $payload
     * @return void
     */
    public function track(array $payload): void;

    /**
     * Check if client is properly configured
     *
     * @return bool
     */
    public function isConfigured(): bool;
}

