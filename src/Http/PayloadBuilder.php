<?php

namespace surfgeo\Laravel\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayloadBuilder
{
    /**
     * Build tracking payload from request and response
     */
    public function build(Request $request, $response): array
    {
        return [
            'timestamp' => time(),
            'path' => $this->getPath($request),
            'method' => $request->method(),
            'status_code' => $response->status(),
            'user_agent' => $request->userAgent() ?? 'Unknown',
            'referrer' => $request->header('referer'),
            'request_id' => (string) Str::uuid(),
        ];
    }

    /**
     * Get clean path without query string
     */
    protected function getPath(Request $request): string
    {
        // Get path without query string
        $path = $request->path();

        // Add leading slash if missing
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        // Remove trailing slash (except root)
        if (strlen($path) > 1 && str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }

        return $path;
    }
}

