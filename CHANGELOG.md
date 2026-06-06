# Changelog

All notable changes to the Genuka Pay PHP SDK will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-06-06

### Added

- **PayinsResource** — create a payin (`POST /api/v1/payments`), list payins, check status by track ID
- **PayoutsResource** — create a payout (`POST /api/v1/payouts`), list payouts, get by ID, cancel
- **CheckoutResource** — create hosted checkout sessions
- **HMAC request signing** — every request is signed with `X-Public-Key`, `X-Timestamp`, and `X-Signature` headers via `Signature::generate()`
- **Idempotency support** — pass an `$idempotencyKey` string to `create()` methods to safely retry requests
- **PHPDoc array shapes** on all resource `create()` methods — exact field contracts matching the backend API validation rules
- Proprietary license (Genuka SAS)

[0.1.0]: https://github.com/genuka/pay-sdk-php/releases/tag/v0.1.0
