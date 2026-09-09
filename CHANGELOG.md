# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.1.0] - 2026-09-10

### 🚀 Refactoring & Performance Optimizations
- **Transport Layer Deduplication**: Introduced `TransportClientTrait` to eliminate code duplication between `Client` (Symfony HttpClient) and `PsrTransportClient` (PSR-18). Standardized URL construction (`buildUrl`) and HTTP status exception handling (`handleResponseStatus`).
- **Centralized Patch Input Normalization**: Added `PatchInputNormalizer` to handle consistent scalar conversion for `BackedEnum` values and `DateTimeInterface` instances across all controller `patching()` methods.
- **Smart URL & Port Resolution**: Enhanced `Config` to automatically infer standard default ports (`80` for HTTP, `443` for HTTPS) using `parse_url()` when no explicit port is provided.
- **Factory Instance Reuse**: Refactored `WebhookParser` and DTO processing to reuse pre-instantiated DTO factories rather than creating new objects on every parse iteration.

## [2.0.0] - 2026-09-08

### 🚨 BREAKING CHANGES
- **Magic Property Removal**: Removed `__get()` magic method and `@property` PHPDoc annotations on `PlankaClient`. Access to all controllers is now strictly typed through explicit method calls (e.g. `$client->project()`, `$client->card()`, `$client->board()`).
- **Controller Constructors**: Removed unused `Config $config` argument from all controller constructors (`Board`, `Card`, `Project`, etc.). Controllers now accept only `TransportClientInterface $client`.
- **Transport Dependency**: Updated controllers and `PlankaClient` to depend on `TransportClientInterface` instead of the concrete `Client` class.

### ✨ Added
- **Fluent Builder API**: Added `CardBuilder`, `BoardBuilder`, and `ProjectBuilder` for step-by-step entity construction via Fluent setters (`$client->card()->builder('Name')->setDescription('...')`).
- **Transport Middleware Stack**: Added `TransportMiddlewareInterface` and `MiddlewareStackTransportClient` for pipeline processing (logging, retries, metrics) surrounding HTTP transport requests.
- **Typed Creation Inputs**: Added `CardCreateInput`, `BoardCreateInput`, and `ProjectCreateInput` DTOs for type-safe creation operations.
- **App Bootstrap Endpoint**: Added `$client->getBootstrap()` (`BootstrapDto`) returning OIDC settings, active user limits, and server version.
- **Strict Hydration Exception**: Added `PlankaHydrationException` for informative error reporting on DTO payload hydration failures.
- **2FA / TOTP Authentication**: Added 2FA TOTP support during login via `AuthenticateResultDto` (`$result->requiresTotp()`, `$result->requiresTerms()`), `$client->verifyTotp()`, and `$client->acceptTerms()`.
- **User TOTP & Trusted Devices Management**: Added `setupTotp()`, `enableTotp()`, `disableTotp()`, `regenerateTotpRecoveryCodes()`, `listTrustedDevices()`, and `deleteTrustedDevice()` to `User` controller.
- **PSR-18 / PSR-17 Transport**: Added `PsrTransportClient` implementing `TransportClientInterface` for seamless integration with any PSR-18 HTTP client (Guzzle, Symfony, etc.) and PSR-17 factories.
- **Unified Exception Interface**: Added `PlankaSdkExceptionInterface` extending `\Throwable`. All SDK exceptions now implement this interface for simplified error handling.
- **Token Storage Abstraction**: Introduced `TokenStorageInterface` and `InMemoryTokenStorage` to separate authentication state from `Config`.
- **HTTP Error Unit Tests**: Added unit tests covering response status codes `400`, `401`, `403`, `404`, `422`, `500`, and default error exceptions.
- **Composer Scripts**: Added `composer check` and `composer analyse` scripts for code quality and static analysis verification.
- **DTO Payload Reference Docs**: Added `@see Payload structure:` annotations to all 19 DTO factories for improved IDE inline documentation and debugging.

### 🐛 Fixed
- Fixed missing `toArray()` method on `CardDto` and `UserDto`.
- Fixed mismatched named parameters in `CardLabelDeleteAction`, `CardMembershipDeleteAction`, `CustomFieldUpdateAction`, and `CustomFieldDeleteAction`.
- Fixed missing `message` property on `TestResultDto` and `TestResultDtoFactory`.
- Fixed `GetInfoAction` / `ServerInfoDtoFactory` handling of server response status code.
- Fixed resource tracking and cleanup logic in `PlankaIntegrationTest`.
