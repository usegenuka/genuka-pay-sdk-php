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
        // Try to extract validation errors (422) from JSON response
        if ($this->statusCode === 422 && is_string($this->responseBody)) {
            $decoded = @json_decode($this->responseBody, true);
            if (is_array($decoded) && isset($decoded['errors'])) {
                $errors = [];
                foreach ($decoded['errors'] as $field => $messages) {
                    if (is_array($messages)) {
                        $errors[] = implode(' ', $messages);
                    } else {
                        $errors[] = (string) $messages;
                    }
                }
                if (! empty($errors)) {
                    return implode(' | ', $errors);
                }
            }
        }

        // Try to extract message field from JSON response
        if (is_string($this->responseBody)) {
            $decoded = @json_decode($this->responseBody, true);
            if (is_array($decoded) && isset($decoded['message'])) {
                return $decoded['message'];
            }
        }

        return "Genuka API request failed with status {$this->statusCode}";
    }
}
