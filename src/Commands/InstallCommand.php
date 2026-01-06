<?php

namespace surfgeo\Laravel\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'surfgeo:install';

    /**
     * The console command description.
     */
    protected $description = 'Install surfgeo AI Tracking package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing surfgeo AI Tracking...');

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'surfgeo-config',
            '--force' => true,
        ]);

        $this->info('Config file published!');

        // Prompt for script key
        $scriptKey = $this->ask('Enter your surfgeo script key (or press Enter to skip)');

        if ($scriptKey) {
            $this->updateEnvFile('SURFGEO_SCRIPT_KEY', $scriptKey);
            $this->info('Script key added to .env');
        }

        $this->newLine();
        $this->info('✓ surfgeo installed successfully!');
        $this->info('Next steps:');
        $this->line('1. Add SURFGEO_SCRIPT_KEY to your .env file');
        $this->line('2. Run: php artisan surfgeo:test');

        return self::SUCCESS;
    }

    /**
     * Update .env file with key-value pair
     */
    protected function updateEnvFile(string $key, string $value): void
    {
        $envFile = base_path('.env');

        if (!file_exists($envFile)) {
            return;
        }

        $content = file_get_contents($envFile);

        if (str_contains($content, $key . '=')) {
            // Update existing
            $content = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $content
            );
        } else {
            // Append new
            $content .= "\n{$key}={$value}\n";
        }

        file_put_contents($envFile, $content);
    }
}

