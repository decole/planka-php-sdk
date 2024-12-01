# Example Subscribe and Unsubscribe user on cards

```php
<?php

declare(strict_types=1);

// before run `composer install`

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use function Fp\Collection\map;

// copy config.example.php to config.php and setup for you
$config = include(__DIR__.'/config.php');

require __DIR__ . '/../vendor/autoload.php';

$config = new Config(
    user: $config['login'],
    password: $config['password'],
    baseUri: $config['uri'],
    port: $config['port']
);
$client = new PlankaClient($config);

if($client->getInfo()->getStatusCode() !== 200) {
    die('Planka server not connected!');
}

if (!$client->authenticate()) {
    die('User credentials not corrected!');
}

$list = $client->project->list();

$project = $list->items[0];

$projectInfo = $client->project->get($project->id);

$boards = [];

foreach ($list->included->boards as $item) {
    if ($item->projectId === $project->id) {
        $boards[] = $item;
    }
}

$board = $boards[0];

$boardInfo = $client->board->get($board->id);

$user = $boardInfo->included->users[0];
$userId = $user->id;

$cards = [];

foreach ($boardInfo->included->cards as $item) {
    // if user always subscribed - return SERVER ERROR 400
    try {
        $cardId = $item->id;
        // subscribe user on cards
        $client->card->subscribe($cardId, $userId);
    } catch (Throwable $e) {}
}

$boardInfo = $client->board->get($board->id);

// on $boardInfo->included->cards not have info by memberships
foreach ($boardInfo->included->cards as $item) {
    // see memberships at card info
    $cardInfo = $client->card->get($item->id);

    $info = [
        'cardId' => $cardInfo->id,
        'cardName' => $cardInfo->name,
        // membershipId not equal userId
        'memberships' => $cardInfo->included->cardMemberships,
        'userId' => map($cardInfo->included->cardMemberships, fn(CardMembershipDto $dto) => [
            'membershipId' => $dto->id,
            'userId' => $dto->userId,
        ]),
    ];

    var_dump($info);
}

// If you need unsubscribe user on cards use this - $client->card->unsubscribe($cardId, $userId);
// unsubscribe

$boardInfo = $client->board->get($board->id);

foreach ($boardInfo->included->cards as $item) {
    // if user always unsubscribed - return SERVER ERROR 400
    try {
        $cardId = $item->id;

        // subscribe user on cards
        $client->card->unsubscribe($cardId, $userId);
    } catch (Throwable $e) {}
}

```


https://github.com/plankanban/planka/blob/v1.16.4/server/config/routes.js
