# Example - Subscribe and Unsubscribe Users on Cards

```php
<?php

declare(strict_types=1);

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    baseUri: 'http://192.168.1.101',
    port: 3000,
    apiKey: 'your_api_key'
);

$client = new PlankaClient($config);

$list = $client->project->list();
$project = $list->items[0];

$boardInfo = $client->board->get($list->included->boards[0]->id);

$userId = $boardInfo->included->users[0]->id;

foreach ($boardInfo->included->cards as $item) {
    try {
        // Subscribe user on cards
        $client->card->subscribe($item->id, $userId);
    } catch (\Throwable $e) {
        // Handle if user is already subscribed
    }
}

// Inspect memberships
foreach ($boardInfo->included->cards as $item) {
    $cardInfo = $client->card->get($item->id);

    var_dump([
        'cardId' => $cardInfo->id,
        'cardName' => $cardInfo->name,
        'memberships' => array_map(
            fn (CardMembershipDto $dto) => ['membershipId' => $dto->id, 'userId' => $dto->userId],
            $cardInfo->included->cardMemberships
        ),
    ]);
}

// Unsubscribe user from cards
foreach ($boardInfo->included->cards as $item) {
    try {
        $client->card->unsubscribe($item->id, $userId);
    } catch (\Throwable $e) {
        // Handle if user is already unsubscribed
    }
}
```
