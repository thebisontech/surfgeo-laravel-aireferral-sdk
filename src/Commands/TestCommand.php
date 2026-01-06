<?php

namespace surfgeo\Laravel\Commands;

use Illuminate\Console\Command;
use surfgeo\Laravel\Client\Contracts\ClientInterface;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'surfgeo:test';

    /**
     * The console command description.
     */
    protected $description = 'Test surfgeo connection';

    protected ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Testing surfgeo connection...');

        // Check if configured
        if (!$this->client->isConfigured()) {
            $this->error('✗ Not configured');
            $this->line('Please set SURFGEO_SCRIPT_KEY in your .env file');
            return self::FAILURE;
        }

        $this->info('✓ Script key configured');

        // Send test payload
        $this->info('Sending test request...');

        try {
            $this->client->track([
                'timestamp' => time(),
                'path' => '/test',
                'method' => 'GET',
                'user_agent' => 'surfgeo-Laravel-Test',
            ]);

            $this->info('✓ Test request sent');
            $this->line('Check your surfgeo dashboard to verify tracking is working');

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('✗ Test failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}

