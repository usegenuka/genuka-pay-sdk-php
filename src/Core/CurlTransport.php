<?php

declare(strict_types=1);

namespace Genuka\Pay\Core;

final class CurlTransport implements HttpTransport
{
    public function send(string $method, string $url, array $headers, ?string $body): array
    {
        $curl = curl_init($url);

        $headerLines = [];
        foreach ($headers as $key => $value) {
            $headerLines[] = "{$key}: {$value}";
        }

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);

        if ($body !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $rawResponse = curl_exec($curl);

        if ($rawResponse === false) {
            $message = curl_error($curl);
            curl_close($curl);

            throw new GenukaApiException(0, ['message' => $message]);
        }

        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        $decoded = json_decode((string) $rawResponse, true);

        return [
            'status' => $status,
            'body' => json_last_error() === JSON_ERROR_NONE ? $decoded : $rawResponse,
        ];
    }
}
