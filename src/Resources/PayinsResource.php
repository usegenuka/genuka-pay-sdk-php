<?php

declare(strict_types=1);

namespace Genuka\Pay\Resources;

use Genuka\Pay\Core\HttpClient;
use Genuka\Pay\Utils\ListParams;

final class PayinsResource
{
    public function __construct(private readonly HttpClient $http) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload, ?string $idempotencyKey = null): mixed
    {
        return $this->http->request('POST', '/api/v1/payments', $payload, idempotencyKey: $idempotencyKey);
    }

    /**
     * @param  array{per_page?: int, sort?: string, filter?: array<string, string|int|bool|null>}  $params
     */
    public function list(array $params = []): mixed
    {
        return $this->http->request('GET', '/api/v1/payments', query: ListParams::format($params));
    }

    public function checkStatus(string $trackId): mixed
    {
        return $this->http->request('GET', '/api/v1/payments/status/'.rawurlencode($trackId));
    }
}
