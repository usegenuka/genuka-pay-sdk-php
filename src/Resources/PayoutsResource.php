<?php

declare(strict_types=1);

namespace Genuka\Pay\Resources;

use Genuka\Pay\Core\HttpClient;
use Genuka\Pay\Utils\ListParams;

final class PayoutsResource
{
    public function __construct(private readonly HttpClient $http) {}

    /**
     * @param  array{
     *     amount: int|float,
     *     currency: string,
     *     country?: string,
     *     payment_method_id?: string,
     *     recipient_phone?: string,
     *     operator_code?: string,
     *     recipient_first_name?: string,
     *     recipient_last_name?: string,
     *     external_id?: string,
     *     description?: string,
     *     metadata?: array<string, mixed>
     * }  $payload
     */
    public function create(array $payload, ?string $idempotencyKey = null): mixed
    {
        return $this->http->request('POST', '/api/v1/payouts', $payload, idempotencyKey: $idempotencyKey);
    }

    /**
     * @param  array{per_page?: int, sort?: string, filter?: array<string, string|int|bool|null>}  $params
     */
    public function list(array $params = []): mixed
    {
        return $this->http->request('GET', '/api/v1/payouts', query: ListParams::format($params));
    }

    public function get(string $id, bool $sync = false): mixed
    {
        return $this->http->request(
            'GET',
            '/api/v1/payouts/'.rawurlencode($id),
            query: $sync ? ['sync' => true] : [],
        );
    }

    public function cancel(string $id): mixed
    {
        return $this->http->request('POST', '/api/v1/payouts/'.rawurlencode($id).'/cancel');
    }
}
