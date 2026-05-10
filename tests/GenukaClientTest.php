<?php

declare(strict_types=1);

namespace Genuka\Pay\Tests;

use Genuka\Pay\GenukaClient;
use PHPUnit\Framework\TestCase;

final class GenukaClientTest extends TestCase
{
    public function test_it_sends_signed_payin_request(): void
    {
        $transport = new FakeTransport(['data' => ['id' => 'payin_1']]);
        $client = new GenukaClient(
            publicKey: 'pk_test_public',
            secretKey: 'sk_test_secret',
            baseUrl: 'https://api.genuka.com/',
            transport: $transport,
        );

        $client->payins()->create([
            'amount' => 200,
            'currency' => 'XAF',
            'payer_phone' => '+237694010263',
            'operator_code' => 'ORANGE_MONEY',
        ], idempotencyKey: 'order-1');

        $request = $transport->requests[0];

        self::assertSame('POST', $request['method']);
        self::assertSame('https://api.genuka.com/api/v1/payments', $request['url']);
        self::assertSame('pk_test_public', $request['headers']['X-Public-Key']);
        self::assertSame('order-1', $request['headers']['Idempotency-Key']);
        self::assertMatchesRegularExpression('/^[0-9]+$/', $request['headers']['X-Timestamp']);
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $request['headers']['X-Signature']);
        self::assertSame(
            '{"amount":200,"currency":"XAF","payer_phone":"+237694010263","operator_code":"ORANGE_MONEY"}',
            $request['body'],
        );
    }

    public function test_it_uses_staging_base_url_by_default(): void
    {
        $transport = new FakeTransport;
        $client = new GenukaClient(
            publicKey: 'pk_test_public',
            secretKey: 'sk_test_secret',
            transport: $transport,
        );

        $client->payins()->list();

        self::assertSame(
            'https://staging-api-pay.genuka.com/api/v1/payments',
            $transport->requests[0]['url'],
        );
    }

    public function test_it_formats_list_query_params(): void
    {
        $transport = new FakeTransport;
        $client = new GenukaClient(
            publicKey: 'pk_test_public',
            secretKey: 'sk_test_secret',
            baseUrl: 'https://api.genuka.com',
            transport: $transport,
        );

        $client->payins()->list([
            'per_page' => 20,
            'sort' => '-created_at',
            'filter' => [
                'status' => 'SUCCESS',
            ],
        ]);

        self::assertSame(
            'https://api.genuka.com/api/v1/payments?per_page=20&sort=-created_at&filter%5Bstatus%5D=SUCCESS',
            $transport->requests[0]['url'],
        );
    }
}
