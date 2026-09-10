# PHP PLANKA REST API SDK (v2.x)

An easy-to-use PHP SDK for accessing Planka's REST API.

> ⚠️ **Version Notice & Compatibility:**
> - **Planka v1 Support:** Support for Planka v1 is discontinued in the main branch. The SDK for Planka v1 is maintained as-is in the `v1` branch (SDK v1.x).
> - **Planka v2 Support:** SDK 2.x is designed and optimized for **Planka v2**. Tested on **Planka Community v2.2.1**.
> - **TOTP & OIDC / SSO Notice:** Two-Factor Authentication (TOTP) and OpenID Connect (OIDC / SSO) features are implemented according to Planka v2 OpenAPI specification, but have not been fully verified in automated live integration test suites. If you encounter any bugs or unexpected behavior with TOTP or OIDC, please **open an Issue on GitHub** with a detailed description, error logs, and reproduction steps.

---

## Installation

### For Planka v2 (SDK v2.x — Recommended)

To install the SDK for **Planka v2**:

```bash
composer require decole/planka-php-sdk:^2.0
```

Or default (installs latest v2.x):

```bash
composer require decole/planka-php-sdk
```

---

### For Planka v1 (SDK v1.x — Legacy)

If your server runs **Planka v1**, install the `1.x` version of the SDK from the legacy branch:

```bash
composer require decole/planka-php-sdk:^1.3
```

---

## Authentication

SDK 2.x supports two authentication methods for Planka v2:

### 1. Username & Password (JWT)

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'admin@example.com',
    password: 'secure_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$planka = new PlankaClient($config);
$result = $planka->authenticate();

if (!$result->success) {
    if ($result->requiresTotp()) {
        $result = $planka->verifyTotp($result->pendingToken, '123456');
    }

    if ($result->requiresTerms()) {
        $terms = $planka->terms()->get();
        $result = $planka->acceptTerms($result->pendingToken, $terms->signature);
    }
}

// Get list of projects
$projects = $planka->project()->list();
```

### 2. User API Key (Planka v2)

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_user_api_key_here'
);

$planka = new PlankaClient($config);

// API Key is automatically included in X-Api-Key headers
$projects = $planka->project()->list();
```

---

### Configuration & Architecture Differences (SDK v1.x vs v2.x)

| Feature / Setting | SDK v1.x (Legacy) | SDK v2.x (Current) |
| :--- | :--- | :--- |
| **Planka Version Support** | Planka v1.x | **Planka v2.x** |
| **Authentication Methods** | Username & Password (JWT) only | **JWT** OR **User API Key** (`apiKey` parameter) |
| **Config Instantiation** | `new Config(user, password, baseUri, port)` | `new Config(user, password, baseUri, port, apiKey, tokenStorage)` |
| **Mandatory Auth Call** | Always required `$planka->authenticate()` | Required only for JWT. **Skipped** when using `apiKey`. |
| **Transport Injection** | Standard Symfony HttpClient | Supports custom `TransportClientInterface` or PSR-18/17 via `PsrTransportClient` for mocking/unit testing |
| **Exception Handling** | Basic `\Exception` inheritance | Unified `PlankaSdkExceptionInterface` for all SDK exceptions |
| **API Endpoints & Features** | Standard boards/cards | Adds **Webhooks**, **Base Custom Fields**, **Notification Services**, **System Config**, **Card Duplication**, etc. |

---

## Advanced Usage

### 1. Unified Exception Handling

All SDK exceptions implement `Planka\Bridge\Exceptions\PlankaSdkExceptionInterface`, allowing you to catch any SDK-related error with a single `catch` block:

```php
use Planka\Bridge\Exceptions\PlankaSdkExceptionInterface;
use Planka\Bridge\Exceptions\PlankaNotFoundException;
use Planka\Bridge\Exceptions\PlankaValidationException;

try {
    $card = $planka->card()->get('invalid-id');
} catch (PlankaNotFoundException $e) {
    echo "Resource not found: " . $e->getMessage();
} catch (PlankaValidationException $e) {
    echo "Validation error (" . $e->getStatusCode() . "): " . $e->getMessage();
} catch (PlankaSdkExceptionInterface $e) {
    echo "SDK Error (" . $e->getStatusCode() . "): " . $e->getMessage();
}
```

### 2. Flexible Network Layer: Native Symfony HttpClient & PSR-18 / PSR-17 Transport

Planka SDK is transport-agnostic. It works out-of-the-box with **Symfony HttpClient**, but seamlessly integrates with any **PSR-18 HTTP Client** (such as Guzzle, Buzz, or PSR-18 adapters) via `PsrTransportClient`:

#### A. Custom Symfony HttpClient (Timeouts, Proxies, HTTP/2):
```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Client;
use Symfony\Component\HttpClient\HttpClient;

$symfonyClient = HttpClient::create([
    'timeout' => 10.0,
    'proxy' => 'http://proxy.corp:8080',
]);

$transport = new Client(config: $config, client: $symfonyClient);
$planka = new PlankaClient(config: $config, client: $transport);
```

#### B. PSR-18 Client with Guzzle 7:
```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\PsrTransportClient;

$guzzleClient = new GuzzleClient(['timeout' => 10.0]);
$httpFactory = new HttpFactory();

$psrTransport = new PsrTransportClient(
    config: $config,
    httpClient: $guzzleClient,    // Psr\Http\Client\ClientInterface
    requestFactory: $httpFactory, // Psr\Http\Message\RequestFactoryInterface
    streamFactory: $httpFactory,  // Psr\Http\Message\StreamFactoryInterface
);

$planka = new PlankaClient(config: $config, client: $psrTransport);
```

> 📖 **Full Transport & Testing Guide:** See [docs/TRANSPORT_CLIENTS.md](docs/TRANSPORT_CLIENTS.md) for Buzz, Nyholm, Symfony PSR-18 adapter, and PHPUnit unit testing mock examples.

### 3. Custom Token Storage

You can provide a custom implementation of `TokenStorageInterface` to persist JWT authentication tokens or API keys across HTTP requests or sessions:

```php
use Planka\Bridge\Auth\TokenStorageInterface;

class CustomTokenStorage implements TokenStorageInterface
{
    public function getAuthToken(): ?string { return $_SESSION['planka_jwt'] ?? null; }
    public function setAuthToken(?string $authToken): void { $_SESSION['planka_jwt'] = $authToken; }
    public function getApiKey(): ?string { return null; }
    public function setApiKey(?string $apiKey): void {}
}

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    tokenStorage: new CustomTokenStorage()
);
```

### 4. Type-Safe Partial Updates (Patch Input DTOs)

For partial entity updates via `patching()`, you can use strongly-typed Input DTOs (`BoardPatchInput`, `CardPatchInput`, `ProjectPatchInput`) or associative arrays:

```php
use Planka\Bridge\Inputs\CardPatchInput;
use Planka\Bridge\Inputs\BoardPatchInput;

// Using strongly-typed Patch Input DTO
$card = $planka->card()->patching(
    cardId: '1357158568008091264',
    map: new CardPatchInput(
        name: 'Updated Card Title',
        isClosed: true
    )
);

// Or using an associative array
$board = $planka->board()->patching(
    boardId: '1357158568008091265',
    map: ['name' => 'Renamed Board']
);
```

### 5. Type-Safe Creation Inputs & Fluent Builder API (`CardBuilder`, `BoardBuilder`, `ProjectBuilder`)

When creating projects, boards, or cards, you can choose between 3 flexible options: simple positional arguments, strongly-typed Creation Input DTOs, or step-by-step Fluent Builders:

```php
use Planka\Bridge\Inputs\CardCreateInput;
use Planka\Bridge\Inputs\BoardCreateInput;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;

// Option 1: Simple positional arguments (Fastest for single fields)
$card = $planka->card()->create(listId: '1357158568008091266', nameOrInput: 'Implement OAuth2');

// Option 2: Strongly-typed Creation Input DTO
$card = $planka->card()->create(
    listId: '1357158568008091266',
    nameOrInput: new CardCreateInput(
        name: 'Implement OAuth2 Flow',
        position: 1,
        description: 'Detailed specification for OAuth2 flow',
        type: BoardDefaultCardTypeEnum::PROJECT
    )
);

// Option 3: Step-by-step Fluent Builder API
$card = $planka->card()->create(
    listId: '1357158568008091266',
    nameOrInput: $planka->card()->builder('Implement OAuth2 Flow')
        ->setPosition(1)
        ->setDescription('Detailed specification for OAuth2 flow')
        ->setType(BoardDefaultCardTypeEnum::PROJECT)
);
```

### 6. Transport Middleware Pipeline

You can inject custom HTTP transport middlewares into `PlankaClient` for logging (PSR-3 Monolog), retrying transient network errors, or collecting request metrics:

```php
use Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware;
use Planka\Bridge\TransportClients\Middleware\RetryMiddleware;

$planka = new PlankaClient(
    config: $config,
    middlewares: [
        new PsrLoggerMiddleware($monologLogger),
        new RetryMiddleware(maxRetries: 3),
    ]
);
```

### 7. Parsing Incoming Webhooks (`WebhookParser`)

You can parse incoming HTTP Webhook JSON payloads received from the Planka server into typed DTOs using `WebhookParser`:

```php
use Planka\Bridge\Webhook\WebhookParser;

$parser = new WebhookParser();
$event = $parser->parse($requestJsonString);

if ($event->isCardCreated()) {
    echo "Card created: {$event->card->name}\n";
}
```

### 8. Raw Response Diagnostics (`$_rawResponse`)

Every DTO in the SDK includes a public `$_rawResponse` property.
This diagnostic property holds the complete, unparsed associative array received from the Planka API response. It is useful for debugging, logging, or verifying whether all API response fields are properly hydrated into DTO properties:

```php
$card = $planka->card()->get('1854744331521361455');

// Access strongly-typed DTO properties:
echo $card->name;

// Inspect the raw server payload for diagnostics:
var_dump($card->_rawResponse);
```

---

## Controllers & Features

All Planka API endpoints are accessible via explicit getter methods on `PlankaClient`:

- `$planka->project()` — Manage projects (`list`, `create`, `get`, `update`, `delete`, `updateBackground`)
- `$planka->projectManager()` — Manage project managers (`create`, `delete`)
- `$planka->board()` — Manage boards (`create`, `get`, `update`, `delete`, `patching`)
- `$planka->boardList()` — Manage lists (`create`, `update`, `delete`, `clear`, `moveCards`, `sort`)
- `$planka->boardMembership()` — Manage board memberships (`create`, `update`, `delete`)
- `$planka->card()` — Manage cards (`create`, `get`, `update`, `delete`, `duplicate`, `patching`, `readNotifications`, `subscribe`, `unsubscribe`)
- `$planka->cardAction()` — Fetch card activity history
- `$planka->cardLabel()` — Add and remove labels on cards
- `$planka->cardTask()` — Manage task lists within cards
- `$planka->cardMembership()` — Manage members assigned to cards
- `$planka->comment()` — Add, update and delete comments on cards
- `$planka->attachment()` — Upload, update and delete attachments
- `$planka->label()` — Manage board labels
- `$planka->user()` — Manage users (`list`, `create`, `get`, `update`, `createApiKey`, etc.)
- `$planka->webhook()` — **(New in v2)** Manage webhooks (`list`, `create`, `update`, `delete`)
- `$planka->baseCustomFieldGroup()` — **(New in v2)** Base custom field groups in projects
- `$planka->customFieldGroup()` — **(New in v2)** Custom field groups on boards/cards
- `$planka->customField()` — **(New in v2)** Custom fields inside groups
- `$planka->notification()` — User notifications (`list`, `getOne`, `markIsRead`, `markIsNotRead`, `readAll`)
- `$planka->notificationService()` — **(New in v2)** External notification services (Slack, Discord, Webhooks)
- `$planka->systemConfig()` — **(New in v2)** Planka application settings and SMTP testing

---

## Documentation & Examples

- [Enterprise Architecture & Production Readiness](docs/ENTERPRISE_ARCHITECTURE.md)
- [Transport Layer Architecture & Multiple HTTP Clients](docs/TRANSPORT_CLIENTS.md)
- [Two-Factor Authentication (2FA / TOTP) & Trusted Devices](docs/TWO_FACTOR_AUTHENTICATION.md)
- [API Key Authentication](docs/API_KEY_AUTHENTICATION.md)
- [Partial Updates with Patch Input DTOs](docs/PATCH_INPUTS.md)
- [Webhooks Management](docs/WEBHOOKS_MANAGEMENT.md)
- [Custom Fields Management](docs/CUSTOM_FIELDS_MANAGEMENT.md)
- [Delete Empty Boards](docs/DELETE_EMPTY_BOARD.md)
- [Add & Manage Cards on Board](docs/ADD_NEW_CARD_ON_BOARD.md)
- [Subscribe / Unsubscribe Users on Cards](docs/SUBSCRIBE_MEMBERSHIP_TO_CARD.md)

> 💡 **Comprehensive Examples:** A complete end-to-end integration script demonstrating usage of all SDK controllers and API endpoints is available in [`tests/index.php`](tests/index.php).

You can also run integration tests against your Planka instance:
```bash
cp tests/config.example.php tests/config.php
# Edit tests/config.php with your credentials
composer test-integration
```

---

## Testing & Quality

### Unit Tests (Isolated, Mock-based)

Unit tests run locally without needing a connection to a live Planka server. They verify DTO hydration, controller behaviors, and API payload compilation using real JSON response fixtures recorded from Planka v2.

```bash
composer test
# Or directly via PHPUnit:
vendor/bin/phpunit --testsuite=Unit
```

### Integration Tests (Live Server Verification)

Integration tests perform real-world SDK verification against a live Planka v2 instance. To verify SDK health on your own Planka server, you can use two complementary testing methods:

1. **PHPUnit Integration Test Suite (`composer test-integration`)**:
   Runs [`tests/Integration/PlankaIntegrationTest.php`](tests/Integration/PlankaIntegrationTest.php), performing a safe end-to-end lifecycle test (creating, updating, inspecting, and deleting projects, boards, columns, cards, task lists, labels, attachments, custom fields, webhooks, and notification services) with strict safety tracking guards.

2. **Standalone Test Script (`php tests/index.php`)**:
   Runs [`tests/index.php`](tests/index.php), a standalone CLI script that executes end-to-end operations and dumps formatted DTO structures and `_rawResponse` payloads directly to the terminal for debugging.

**Setup:**
1. Copy the example configuration file:
   ```bash
   cp tests/config.example.php tests/config.php
   ```
2. Edit `tests/config.php` to specify your Planka server URI, port, login, and password:
   ```php
   return [
       'uri' => 'http://192.168.1.100',
       'port' => 3000,
       'login' => 'user@example.com',
       'password' => 'your_password',
   ];
   ```
3. Run the integration test suite:
   ```bash
   composer test-integration
   # Or run the standalone CLI test script:
   php tests/index.php
   ```

### Code Quality & Static Analysis

Run all quality checks (CS Fixer dry-run, Psalm static analysis, and Unit tests):
```bash
composer check
```

Or individual checks:
```bash
composer analyse    # Runs Psalm static analysis
composer fix-cs     # Formats code style
```

---

## Acknowledgements & Community Contributions

This SDK v2 release incorporates valuable improvements, endpoint refinements, and architectural ideas inspired by the community fork [`steglasaurous/planka-php-sdk`](https://github.com/steglasaurous/planka-php-sdk) (such as TOTP 2FA flow structures, OpenAPI `/api/config` alignment, and extended user profile properties).

---

## Contributing & Support

Contributions are very welcome! Whether you are fixing a bug, adding a new Planka v2 feature, or improving documentation:

1. **Fork & Clone:** Fork the repository on GitHub and clone it locally.
2. **Create a Branch:** `git checkout -b feature/my-feature` or `fix/bug-fix`.
3. **Make Changes & Test:** Ensure all quality checks pass before pushing:
   - `composer test` (Runs PHPUnit Unit tests)
   - `./vendor/bin/psalm --no-cache` (Runs Psalm static analysis)
   - `composer fix-cs` (Formats code style)
4. **Submit a Pull Request:** Push your branch and open a PR against `master`.

For more details, see [CONTRIBUTING.md](CONTRIBUTING.md).

### Support & License
- 🐛 **Bug Reports & Requests:** Please open an Issue on [GitHub Issues](https://github.com/decole/planka-php-sdk/issues).
- 📄 **License:** Released under the [AGPL-3.0 License](https://choosealicense.com/licenses/agpl-3.0/).
