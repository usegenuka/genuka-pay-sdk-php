<?php

declare(strict_types=1);

namespace Genuka\Pay\Core;

final class Signature
{
    public static function sign(
        string $secretKey,
        string $timestamp,
        string $method,
        string $path,
        string $rawBody = '',
    ): string {
        return hash_hmac('sha256', $timestamp.strtoupper($method).$path.$rawBody, $secretKey);
    }

    public static function normalizePath(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parts = parse_url($path);
            $normalizedPath = $parts['path'] ?? '/';
            $query = isset($parts['query']) ? "?{$parts['query']}" : '';

            return $normalizedPath.$query;
        }

        return str_starts_with($path, '/') ? $path : "/{$path}";
    }
}
