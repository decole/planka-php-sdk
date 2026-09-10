# Webhooks Management (Planka v2)

Planka v2 includes full support for system webhooks (`/api/webhooks`).

---

## 1. Prerequisites: Client Setup & Authentication

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

// Option A: API Key Authentication (Recommended)
$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_api_key'
);
$client = new PlankaClient($config);

// OR Option B: Username & Password (JWT)
// $config = new Config(user: 'admin@example.com', password: 'password', baseUri: 'http://192.168.1.100', port: 3000);
// $client = new PlankaClient($config);
// $client->authenticate();
```

---

## 2. List All Webhooks

```php
$webhooks = $client->webhook()->list();

foreach ($webhooks as $webhook) {
    echo "Webhook: {$webhook->name} -> {$webhook->url}\n";
}
```

---

## 3. Create Webhook

```php
$webhook = $client->webhook()->create(
    name: 'Automation Webhook',
    url: 'https://example.com/webhook-receiver',
    accessToken: 'secret_token_123',
    events: 'cardCreate,cardUpdate,cardDelete',
    excludedEvents: 'userCreate'
);

echo "Created Webhook ID: {$webhook->id}\n";
```

---

## 4. Update Webhook

```php
// Full / simple update
$updatedWebhook = $client->webhook()->update(
    webhookId: $webhook->id,
    name: 'Updated Webhook Name',
    url: 'https://example.com/new-receiver'
);

// Type-safe partial update with WebhookPatchInput
use Planka\Bridge\Inputs\WebhookPatchInput;

$patchedWebhook = $client->webhook()->patching(
    webhookId: $webhook->id,
    map: new WebhookPatchInput(
        events: ['cardCreate', 'cardDelete']
    )
);
```

---

## 5. Delete Webhook

```php
$deletedWebhook = $client->webhook()->delete(webhookId: $webhook->id);
```

---

## 6. Parsing Incoming Webhook Events (`WebhookParser`)

You can parse incoming HTTP Webhook payloads sent from the Planka server using `WebhookParser`:

```php
use Planka\Bridge\Webhook\WebhookParser;

$parser = new WebhookParser();

// Pass raw HTTP request JSON body
$event = $parser->parse($requestBody);

echo "Event Type: {$event->eventType}\n";

if ($event->isCardCreated()) {
    $card = $event->card;
    echo "Card created: {$card->name} (ID: {$card->id})\n";
} elseif ($event->isCardMoved()) {
    $card = $event->card;
    echo "Card moved: {$card->name}\n";
}
```

---

## 7. HMAC Signature Verification

To verify that the webhook payload originated from your Planka server and was not tampered with, use `verifySignature`:

```php
use Planka\Bridge\Webhook\WebhookParser;

$parser = new WebhookParser();

$rawBody = file_get_contents('php://input');
$signatureHeader = $_SERVER['HTTP_X_PLANKA_SIGNATURE'] ?? '';
$secretKey = 'your_configured_webhook_secret';

if (!$parser->verifySignature($rawBody, $secretKey, $signatureHeader)) {
    http_response_code(403);
    exit('Invalid signature');
}

$event = $parser->parse($rawBody);
```

---

## 8. PSR-14 Event Dispatcher (`WebhookEventDispatcher`)

`WebhookEventDispatcher` integrates Planka webhook events directly into any PSR-14 Event Dispatcher (such as Symfony EventDispatcher or Laravel Event):

```php
use Planka\Bridge\Webhook\WebhookEventDispatcher;
use Planka\Bridge\Webhook\WebhookEventDto;

$dispatcher = new WebhookEventDispatcher();

// Pass raw payload and your PSR-14 EventDispatcher instance (or callable listener)
$event = $dispatcher->dispatch($requestBody, $psr14EventDispatcher);

// Or using a simple callable listener:
$dispatcher->dispatch($requestBody, function (WebhookEventDto $event) {
    if ($event->isCardCreated()) {
        // Handle card creation asynchronously
    }
});
```
