<?php

declare(strict_types=1);
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

/** @var \Illuminate\Foundation\Application */
$app = require __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

$baseUrl = rtrim(
    $argv[1] ?? config('app.url', 'http://localhost:8080')
);

$endpoint = "$baseUrl/broadcasting/auth";

echo "Endpoint: $endpoint";

$cases = [
    'Valid channel_name' => [
        'socket_id' => '123.456',
        'channel_name' => 'private-test'
    ],
    'Invalid channel_name (array)' => [
        'socket_id' => '123.456',
        'channel_name' => ['private-test']
    ]
];

foreach ($cases as $case => $payload) {
    echo "\n=== {$case} ===\n";

    try {
        $response = Http::acceptJson()
            ->timeout(10)
            ->post($endpoint, $payload);

        echo "HTTP Status: {$response->status()}\n";
        echo "Response: " . Str::limit($response->body(), 130) . "\n";
    } catch (\Throwable $e) {
        echo "Exception: " . $e->getMessage();
    }
}