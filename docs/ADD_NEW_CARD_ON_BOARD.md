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

// 3. Add task list and tasks to card
$taskList = $client->cardTask->createTaskList($card->id, 'Checklist');
$task1 = $client->cardTask->create($taskList->id, 'Design schema', 0);
$task2 = $client->cardTask->create($taskList->id, 'Write endpoints', 1);

// 4. Duplicate card (Planka v2 feature)
$duplicatedCard = $client->card->duplicate($card->id);

// 5. Mark notifications as read for card (Planka v2 feature)
$client->card->readNotifications($card->id);
```
