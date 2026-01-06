<?php

namespace surfgeo\Laravel\Tests\Feature;

use surfgeo\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TrackingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'surfgeo.script_key' => 'sk_test_key',
            'surfgeo.enabled' => true,
            'surfgeo.excluded_environments' => [], // Allow tracking in tests
        ]);

        Http::fake();
    }

    public function test_middleware_tracks_request()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        Http::assertSent(function ($request) {
            $data = $request->data();
            return isset($data['path']) && $data['script_key'] === 'sk_test_key';
        });
    }

    public function test_excluded_paths_not_tracked()
    {
        config(['surfgeo.excluded_paths' => ['/health-check']]);

        $this->get('/health-check');

        Http::assertNothingSent();
    }
}

