# Genuka Pay PHP SDK

Server-side PHP SDK for the Genuka Pay API.

Do not use this SDK in frontend code. It signs requests with your application `secretKey`.

## Install

```bash
composer require genuka/pay-sdk
```

## Usage

```php
use Genuka\Pay\GenukaClient;

$genuka = new GenukaClient(
    publicKey: getenv('GENUKA_PUBLIC_KEY'),
    secretKey: getenv('GENUKA_SECRET_KEY'),
);

$payin = $genuka->payins()->create([
    'amount' => 2000,
    'currency' => 'XAF',
    'payer_phone' => '+237694010263',
    'operator_code' => 'ORANGE_MONEY',
    'metadata' => [
        'order_id' => 'ORD-1001',
    ],
]);
```

By default, the SDK targets `https://staging-api-pay.genuka.com`. Pass `baseUrl` to use another environment.

## API

```php
$genuka->payins()->create($payload, idempotencyKey: 'order-1001');
$genuka->payins()->list(['per_page' => 20]);
$genuka->payins()->checkStatus('track-xxx');

$genuka->payouts()->create($payload, idempotencyKey: 'payout-1001');
$genuka->payouts()->list(['per_page' => 20]);
$genuka->payouts()->get('payout-id');
$genuka->payouts()->cancel('payout-id');

$genuka->checkout()->create($payload);
$genuka->checkout()->get('checkout-token');
```

Phone numbers must be international E.164 style, for example `+237694010263`, `+241...`, `+235...`.

## Authentication

The SDK signs every request with:

```text
timestamp + HTTP_METHOD + path_with_query + raw_body
```

Headers sent:

```http
X-Public-Key: pk_live_xxx
X-Timestamp: 1778353137
X-Signature: hmac_sha256_hex
Content-Type: application/json
```

## Development

```bash
composer install
composer test
```
