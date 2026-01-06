<?php

namespace surfgeo\Laravel\Tests\Unit;

use surfgeo\Laravel\Tests\TestCase;
use surfgeo\Laravel\Client\surfgeoClient;
use Illuminate\Support\Facades\Http;

class ClientTest extends TestCase
{
    public function test_client_initialization()
    {
        $client = new surfgeoClient([
            'script_key' => 'sk_test_key',
            'enabled' => true,
        ]);

        $this->assertInstanceOf(surfgeoClient::class, $client);
        $this->assertTrue($client->isConfigured());
    }

    public function test_track_sends_http_request()
    {
        Http::fake();

        $client = new surfgeoClient([
            'script_key' => 'sk_test_key',
            'endpoint' => 'https://test.com/track',
            'enabled' => true,
        ]);

        $client->track([
            'timestamp' => time(),
            'path' => '/test',
            'method' => 'GET',
            'user_agent' => 'Test',
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://test.com/track';
        });
    }

    public function test_track_respects_enabled_flag()
    {
        Http::fake();

        $client = new surfgeoClient([
            'script_key' => 'sk_test_key',
            'enabled' => false,
        ]);

        $client->track(['path' => '/test']);

        Http::assertNothingSent();
    }
}

