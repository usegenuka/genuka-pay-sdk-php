<?php

declare(strict_types=1);

namespace Genuka\Pay;

use Genuka\Pay\Core\HttpClient;
use Genuka\Pay\Core\HttpTransport;
use Genuka\Pay\Resources\CheckoutResource;
use Genuka\Pay\Resources\PayinsResource;
use Genuka\Pay\Resources\PayoutsResource;
use Genuka\Pay\Resources\WebhookEndpointsResource;

final class GenukaClient
{
    private HttpClient $http;

    public function __construct(
        string $publicKey,
        string $secretKey,
        ?string $baseUrl = null,
        ?HttpTransport $transport = null,
    ) {
        $this->http = new HttpClient(
            publicKey: $publicKey,
            secretKey: $secretKey,
            baseUrl: $baseUrl,
            transport: $transport,
        );
    }

    public function payins(): PayinsResource
    {
        return new PayinsResource($this->http);
    }

    public function payouts(): PayoutsResource
    {
        return new PayoutsResource($this->http);
    }

    public function checkout(): CheckoutResource
    {
        return new CheckoutResource($this->http);
    }

    public function webhookEndpoints(): WebhookEndpointsResource
    {
        return new WebhookEndpointsResource($this->http);
    }
}
