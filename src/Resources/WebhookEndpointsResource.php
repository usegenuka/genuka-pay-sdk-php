<?php

declare(strict_types=1);

namespace Genuka\Pay\Resources;

use Genuka\Pay\Core\HttpClient;

final class WebhookEndpointsResource
{
    public function __construct(private readonly HttpClient $http) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): mixed
    {
        return $this->http->request('POST', '/api/v1/webhook-endpoints', $payload);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function list(array $params = []): mixed
    {
        return $this->http->request('GET', '/api/v1/webhook-endpoints', query: $params);
    }

    public function get(string $id): mixed
    {
        return $this->http->request('GET', '/api/v1/webhook-endpoints/'.rawurlencode($id));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function update(string $id, array $payload): mixed
    {
        return $this->http->request('PUT', '/api/v1/webhook-endpoints/'.rawurlencode($id), $payload);
    }

    public function delete(string $id): mixed
    {
        return $this->http->request('DELETE', '/api/v1/webhook-endpoints/'.rawurlencode($id));
    }

    public function regenerateSecret(string $id): mixed
    {
        return $this->http->request('POST', '/api/v1/webhook-endpoints/'.rawurlencode($id).'/regenerate-secret');
    }
}
