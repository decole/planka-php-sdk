# Example - Delete Empty Boards

This example demonstrates how to find and delete all empty boards across your accessible projects.

---

## Prerequisites: Client Setup & Authentication

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;

require __DIR__ . '/vendor/autoload.php';

// Option A: User API Key (Recommended for maintenance scripts)
$config = new Config(
    baseUri: 'http://192.168.1.101',
    port: 3000,
    apiKey: 'your_api_key'
);
$planka = new PlankaClient($config);

// OR Option B: Username & Password (JWT)
// $config = new Config(
//     user: 'admin@example.com',
//     password: 'password',
//     baseUri: 'http://192.168.1.101',
//     port: 3000
// );
// $planka = new PlankaClient($config);
// $planka->authenticate();

// Request list of all accessible projects
$dto = $planka->project()->list();
$boards = $dto->included->boards;

/** @var BoardItemDto $item */
foreach ($boards as $item) {
    // Request board details including cards
    $board = $planka->board()->get($item->id);

    // List of board cards
    $cardList = $board->included->cards;

    if (empty($cardList)) {
        // Remove empty board
        $planka->board()->delete($item->id);
        echo "Deleted empty board: {$item->name} (ID: {$item->id})\n";
    }
}
```
