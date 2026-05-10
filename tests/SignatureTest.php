<?php

declare(strict_types=1);

namespace Genuka\Pay\Tests;

use Genuka\Pay\Core\Signature;
use PHPUnit\Framework\TestCase;

final class SignatureTest extends TestCase
{
    public function test_it_signs_timestamp_method_path_and_raw_body(): void
    {
        $timestamp = '1778353137';
        $method = 'POST';
        $path = '/api/v1/payments';
        $rawBody = '{"amount":200,"currency":"XAF","payer_phone":"+237694010263"}';
        $secretKey = 'sk_test_secret';

        $expected = hash_hmac('sha256', $timestamp.$method.$path.$rawBody, $secretKey);

        self::assertSame($expected, Signature::sign($secretKey, $timestamp, $method, $path, $rawBody));
    }

    public function test_it_keeps_only_path_and_query_from_full_url(): void
    {
        self::assertSame(
            '/api/v1/payments?limit=10&page=2',
            Signature::normalizePath('https://api.genuka.com/api/v1/payments?limit=10&page=2'),
        );
    }
}
