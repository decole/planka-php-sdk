# Webhooks Management (Planka v2)

Planka v2 includes full support for system webhooks (`/api/webhooks`).

---

## 1. List All Webhooks

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_api_key'
);

$client = new PlankaClient($config);

$webhooks = $client->webhook->list();

foreach ($webhooks as $webhook) {
    echo "Webhook: {$webhook->name} -> {$webhook->url}\n";
}
```

---

## 2. Create Webhook

```php
$webhook = $client->webhook->create(
    name: 'Automation Webhook',
    url: 'https://example.com/webhook-receiver',
    accessToken: 'secret_token_123',
    events: 'cardCreate,cardUpdate,cardDelete',
    excludedEvents: 'userCreate'
);

echo "Created Webhook ID: {$webhook->id}\n";
```

---

## 3. Update Webhook

```php
$updatedWebhook = $client->webhook->update(
    webhookId: $webhook->id,
    name: 'Updated Webhook Name',
    url: 'https://example.com/new-receiver'
);
```

---

## 4. Delete Webhook

```php
$deletedWebhook = $client->webhook->delete(webhookId: $webhook->id);
```
