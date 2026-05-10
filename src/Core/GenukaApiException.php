<?php

declare(strict_types=1);

namespace Genuka\Pay\Core;

use RuntimeException;

final class GenukaApiException extends RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        public readonly mixed $responseBody,
    ) {
        parent::__construct("Genuka API request failed with status {$statusCode}", $statusCode);
    }
}
