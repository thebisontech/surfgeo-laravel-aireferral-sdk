<?php

namespace surfgeo\Laravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use surfgeo\Laravel\Client\Contracts\ClientInterface;

class TrackRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;

        // Set queue connection and name from config
        $this->onConnection(config('surfgeo.queue_connection', 'default'));
        $this->onQueue(config('surfgeo.queue', 'default'));
    }

    /**
     * Execute the job
     */
    public function handle(ClientInterface $client): void
    {
        $client->track($this->payload);
    }

    /**
     * Handle a job failure
     */
    public function failed(\Throwable $exception): void
    {
        // Log failure but don't retry (fire-and-forget)
        if (config('surfgeo.debug')) {
                \Log::error('[surfgeo] Queue job failed', [
                'error' => $exception->getMessage(),
                'payload' => $this->payload,
            ]);
        }
    }
}

