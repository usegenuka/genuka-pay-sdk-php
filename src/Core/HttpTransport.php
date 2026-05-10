<?php

declare(strict_types=1);

namespace Genuka\Pay\Core;

interface HttpTransport
{
    /**
     * @param  array<string, string>  $headers
     * @return array{status: int, body: mixed}
     */
    public function send(string $method, string $url, array $headers, ?string $body): array;
}
