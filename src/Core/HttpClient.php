<?php

declare(strict_types=1);

namespace Genuka\Pay\Core;

final class HttpClient
{
    private const DEFAULT_BASE_URL = 'https://staging-api-pay.genuka.com';

    public function __construct(
        private readonly string $publicKey,
        private readonly string $secretKey,
        private readonly ?string $baseUrl = null,
        private readonly ?HttpTransport $transport = null,
    ) {}

    /**
     * @param  array<string, mixed>|null  $body
     * @param  array<string, string|int|bool|null>  $query
     */
    public function request(
        string $method,
        string $path,
        ?array $body = null,
        array $query = [],
        ?string $idempotencyKey = null,
    ): mixed {
        $method = strtoupper($method);
        $path = $this->buildPath($path, $query);
        $rawBody = $body === null ? '' : json_encode($body, JSON_THROW_ON_ERROR);
        $timestamp = (string) time();
        $signature = Signature::sign(
            secretKey: $this->secretKey,
            timestamp: $timestamp,
            method: $method,
            path: $path,
            rawBody: $rawBody,
        );

        $headers = [
            'Accept' => 'application/json',
            'X-Public-Key' => $this->publicKey,
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
        ];

        if ($rawBody !== '') {
            $headers['Content-Type'] = 'application/json';
        }

        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }

        $response = ($this->transport ?? new CurlTransport)->send(
            method: $method,
            url: $this->baseUrl().$path,
            headers: $headers,
            body: $rawBody === '' ? null : $rawBody,
        );

        if ($response['status'] < 200 || $response['status'] >= 300) {
            throw new GenukaApiException($response['status'], $response['body']);
        }

        return $response['body'];
    }

    /**
     * @param  array<string, string|int|bool|null>  $query
     */
    private function buildPath(string $path, array $query): string
    {
        $path = Signature::normalizePath($path);
        $query = array_filter($query, static fn (mixed $value): bool => $value !== null);

        if ($query === []) {
            return $path;
        }

        $separator = str_contains($path, '?') ? '&' : '?';

        return $path.$separator.http_build_query($query);
    }

    private function baseUrl(): string
    {
        return rtrim($this->baseUrl ?? self::DEFAULT_BASE_URL, '/');
    }
}
