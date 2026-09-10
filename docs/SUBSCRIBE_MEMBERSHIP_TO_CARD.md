# Example - Subscribe and Unsubscribe Users on Cards

---

## Prerequisites: Client Setup & Authentication

```php
<?php

declare(strict_types=1);

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;

require __DIR__ . '/vendor/autoload.php';

// Option A: API Key Authentication (Recommended)
$config = new Config(
    baseUri: 'http://192.168.1.101',
    port: 3000,
    apiKey: 'your_api_key'
);
$client = new PlankaClient($config);

// OR Option B: Username & Password (JWT)
// $config = new Config(user: 'admin@example.com', password: 'password', baseUri: 'http://192.168.1.101', port: 3000);
// $client = new PlankaClient($config);
// $client->authenticate();

$list = $client->project()->list();
$project = $list->items[0];

$boardInfo = $client->board()->get($list->included->boards[0]->id);
$userId = $boardInfo->included->users[0]->id;

// 1. Subscribe user to cards
foreach ($boardInfo->included->cards as $item) {
    try {
        $client->card()->subscribe($item->id, $userId);
    } catch (\Throwable $e) {
        // User already subscribed
    }
}

// 2. Inspect memberships
foreach ($boardInfo->included->cards as $item) {
    $cardInfo = $client->card()->get($item->id);

    var_dump([
        'cardId' => $cardInfo->id,
        'cardName' => $cardInfo->name,
        'memberships' => array_map(
            fn (CardMembershipDto $dto) => ['membershipId' => $dto->id, 'userId' => $dto->userId],
            $cardInfo->included->cardMemberships
        ),
    ]);
}

// 3. Unsubscribe user from cards
foreach ($boardInfo->included->cards as $item) {
    try {
        $client->card()->unsubscribe($item->id, $userId);
    } catch (\Throwable $e) {
        // User already unsubscribed
    }
}
```
