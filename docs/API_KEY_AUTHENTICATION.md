# API Key Authentication (Planka v2)

Planka v2 introduces API Key authentication via the `X-Api-Key` header, allowing scripts and integrations to authenticate without exchanging credentials for a temporary JWT token.

---

## 1. Generating an API Key

You can generate an API Key for a user using JWT authentication or an admin user:

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'admin@example.com',
    password: 'admin_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$client = new PlankaClient($config);
$client->authenticate();

// Generate API key for a specific user ID
$response = $client->user->createApiKey(userId: '1357158568008091264');

$apiKey = $response['included']['apiKey'];
echo "Generated API Key: " . $apiKey . "\n";
// NOTE: Store this API key securely. It will only be returned once by Planka!
```

---

## 2. Using API Key in SDK

Pass the `apiKey` directly to `Config`:

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'D89VszVs_oSS6TdDtYmi0j1LhugOioY40dDVssESO'
);

$client = new PlankaClient($config);

// No need to call ->authenticate()! All requests will include `X-Api-Key` header.
$projects = $client->project->list();
var_dump($projects);
```
