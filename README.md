# PHP PLANKA REST API SDK (v2.x)

An easy-to-use PHP SDK for accessing Planka's REST API.

> ⚠️ **Version Notice & Compatibility:**
> - **Planka v1 Support:** Support for Planka v1 is discontinued in the main branch. The SDK for Planka v1 is maintained as-is in the `v1` branch (SDK v1.x).
> - **Planka v2 Support:** SDK 2.x is designed and optimized for **Planka v2**. Tested on **Planka Community v2.2.1**.

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
$planka->authenticate();

// Get list of projects
$projects = $planka->project->list();
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
$projects = $planka->project->list();
```

---

### Configuration & Architecture Differences (SDK v1.x vs v2.x)

| Feature / Setting | SDK v1.x (Legacy) | SDK v2.x (Current) |
| :--- | :--- | :--- |
| **Planka Version Support** | Planka v1.x | **Planka v2.x** |
| **Authentication Methods** | Username & Password (JWT) only | **JWT** OR **User API Key** (`apiKey` parameter) |
| **Config Instantiation** | `new Config(user, password, baseUri, port)` | `new Config(user, password, baseUri, port, apiKey)` |
| **Mandatory Auth Call** | Always required `$planka->authenticate()` | Required only for JWT. **Skipped** when using `apiKey`. |
| **Transport Injection** | Standard Symfony HttpClient | Supports custom `Client` injection: `new PlankaClient($config, $customTransportClient)` for mocking/unit testing |
| **API Endpoints & Features** | Standard boards/cards | Adds **Webhooks**, **Base Custom Fields**, **Notification Services**, **System Config**, **Card Duplication**, etc. |

---

## Controllers & Features

All Planka API endpoints are organized into clean, strongly-typed controllers:

- `$planka->project` — Manage projects (`list`, `create`, `get`, `update`, `delete`, `updateBackground`)
- `$planka->projectManager` — Manage project managers (`create`, `delete`)
- `$planka->board` — Manage boards (`create`, `get`, `update`, `delete`)
- `$planka->boardList` — Manage lists (`create`, `update`, `delete`, `clear`, `moveCards`, `sort`)
- `$planka->boardMembership` — Manage board memberships (`create`, `update`, `delete`)
- `$planka->card` — Manage cards (`create`, `get`, `update`, `delete`, `duplicate`, `readNotifications`, `subscribe`, `unsubscribe`)
- `$planka->cardAction` — Fetch card activity history
- `$planka->cardLabel` — Add and remove labels on cards
- `$planka->cardTask` — Manage task lists within cards
- `$planka->cardMembership` — Manage members assigned to cards
- `$planka->comment` — Add, update and delete comments on cards
- `$planka->attachment` — Upload, update and delete attachments
- `$planka->label` — Manage board labels
- `$planka->user` — Manage users (`list`, `create`, `get`, `update`, `createApiKey`, etc.)
- `$planka->webhook` — **(New in v2)** Manage webhooks (`list`, `create`, `update`, `delete`)
- `$planka->baseCustomFieldGroup` — **(New in v2)** Base custom field groups in projects
- `$planka->customFieldGroup` — **(New in v2)** Custom field groups on boards/cards
- `$planka->customField` — **(New in v2)** Custom fields inside groups
- `$planka->notification` — User notifications (`list`, `getOne`, `markIsRead`, `markIsNotRead`, `readAll`)
- `$planka->notificationService` — **(New in v2)** External notification services (Slack, Discord, Webhooks)
- `$planka->systemConfig` — **(New in v2)** Planka application settings and SMTP testing

---

## Documentation & Examples

- [API Key Authentication](docs/API_KEY_AUTHENTICATION.md)
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

Integration tests perform full real-world SDK verification against a live Planka v2 instance (creating, updating, and safely cleaning up projects, boards, cards, task lists, custom fields, webhooks, and notification services).

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
   # Or directly via PHPUnit:
   vendor/bin/phpunit --testsuite=Integration
   ```

### Code Quality & Formatting

Static code analysis:
```bash
./vendor/bin/psalm --no-cache
```

Code style fixer:
```bash
composer fix-cs
```

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
- 📄 **License:** Released under the [AGPL-3.0 License](LICENSE).
