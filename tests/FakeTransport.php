<?php

declare(strict_types=1);

namespace Genuka\Pay\Tests;

use Genuka\Pay\Core\HttpTransport;

final class FakeTransport implements HttpTransport
{
    /**
     * @var list<array{method: string, url: string, headers: array<string, string>, body: ?string}>
     */
    public array $requests = [];

    public function __construct(private readonly mixed $responseBody = ['data' => []]) {}

    public function send(string $method, string $url, array $headers, ?string $body): array
    {
        $this->requests[] = [
            'method' => $method,
            'url' => $url,
            'headers' => $headers,
            'body' => $body,
        ];

        return [
            'status' => 200,
            'body' => $this->responseBody,
        ];
    }
}
