### Example - Delete empty board

```php
<?php

use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Client;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'login',
    password: '***************',
    baseUri: 'http://192.168.1.101', // https://your.domain.com
    port: 3000                       // 443
);

$planka = new PlankaClient($config);

$planka->authenticate();

// Only projects and boards assigned to your user are available
$dto = $planka->project->list();

//        dd($dto->items); // list projects

// the list will only contain boards available to your user
$boards = $dto->included->boards;

/** @var BoardItemDto $item */
foreach ($boards as $item) {
    // we request each board separately
    $board = $planka->board->get($item->id);

    // list of board cards
    $cardList = $board->included->cards;

    if (empty($cardList)) {
        // removing a board without cards
        $planka->board->delete($item->id);
    }
}
```

https://github.com/plankanban/planka/blob/v1.16.4/server/config/routes.js

