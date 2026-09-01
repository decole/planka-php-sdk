# Example - Delete empty board

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'admin@example.com',
    password: 'password',
    baseUri: 'http://192.168.1.101',
    port: 3000
);

$planka = new PlankaClient($config);
$planka->authenticate();

// Only projects and boards assigned to your user are available
$dto = $planka->project->list();

$boards = $dto->included->boards;

/** @var BoardItemDto $item */
foreach ($boards as $item) {
    // Request each board details
    $board = $planka->board->get($item->id);

    // List of board cards
    $cardList = $board->included->cards;

    if (empty($cardList)) {
        // Removing a board without cards
        $planka->board->delete($item->id);
    }
}
```
