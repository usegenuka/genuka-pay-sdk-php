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
        $message = $this->extractMessage();
        parent::__construct($message, $statusCode);
    }

    private function extractMessage(): string
    {
        if (is_array($this->responseBody) && isset($this->responseBody['message'])) {
            return $this->responseBody['message'];
        }

        return "Genuka API request failed with status {$this->statusCode}";
    }
}
