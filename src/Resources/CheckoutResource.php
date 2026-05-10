<?php

declare(strict_types=1);

namespace Genuka\Pay\Resources;

use Genuka\Pay\Core\HttpClient;

final class CheckoutResource
{
    public function __construct(private readonly HttpClient $http) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): mixed
    {
        return $this->http->request('POST', '/api/v1/checkout', $payload);
    }

    public function get(string $token): mixed
    {
        return $this->http->request('GET', '/api/v1/checkout/'.rawurlencode($token));
    }
}
