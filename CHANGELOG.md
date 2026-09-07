# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-09-08

### 🚨 BREAKING CHANGES
- **Magic Property Removal**: Removed `__get()` magic method and `@property` PHPDoc annotations on `PlankaClient`. Access to all controllers is now strictly typed through explicit method calls (e.g. `$client->project()`, `$client->card()`, `$client->board()`).
- **Controller Constructors**: Removed unused `Config $config` argument from all controller constructors (`Board`, `Card`, `Project`, etc.). Controllers now accept only `TransportClientInterface $client`.
- **Transport Dependency**: Updated controllers and `PlankaClient` to depend on `TransportClientInterface` instead of the concrete `Client` class.

### ✨ Added
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
