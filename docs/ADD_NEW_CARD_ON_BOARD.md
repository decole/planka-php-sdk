# Example - Create and Manage Cards

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    baseUri: 'http://192.168.1.101',
    port: 3000,
    apiKey: 'your_api_key'
);

$client = new PlankaClient($config);

// 1. Create a new card in list
$card = $client->card->create(
    listId: '1357158568008091266',
    name: 'Implement OAuth2 Login',
    position: 65536
);

// 2. Add description and update card
$card->description = 'Detailed description of OAuth2 integration';
$updatedCard = $client->card->update($card);

// 3. Add tasks to card
$task1 = $client->cardTask->create($card->id, 'Design schema', 0);
$task2 = $client->cardTask->create($card->id, 'Write endpoints', 1);

// 4. Duplicate card (Planka v2 feature)
$duplicatedCard = $client->card->duplicate($card->id);

// 5. Mark notifications as read for card (Planka v2 feature)
$client->card->readNotifications($card->id);
```
