<?php

// before run `composer install`

use Planka\Bridge\Config;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Card\StopWatchDto;

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

$filePath = __DIR__ . '/image.png';

$client->project->list();

$project = $client->project->create('test');

$projectGet = $client->project->get($project->id);
$projectGet->name = 'trim';
$client->project->update($projectGet);

try {
    $projectWithImage = $client->project->updateBackgroundImage($projectGet->id, $filePath);
} catch (Throwable $exception) {
    die('Upload image to project error');
}

// delete project background image
$projectGet->backgroundImage = null;
$projectGet->background = null;
$client->project->update($projectGet);

// add board
$board = $client->board->create($projectGet->id, 'testCard', 1);
$boardGet = $client->board->get($board->item->id);

$client->board->update($boardGet->item->id, 'romb');

// add board list
$list = $client->boardList->create($boardGet->item->id, 'one', 1);

// add card
$card = $client->card->create($list->id, 'card', 1);

// card get
$cardGet = $client->card->get($card->id);

// card update
$cardGet->name = 'limonad';
$cardGet->position = 2;
$cardGet->stopwatch = new StopWatchDto(null, 2);
$cardGet->isSubscribed = true;
$cardGet->description = 'ok!';
$client->card->update($cardGet);

// add spend worked time to card
$client->card->addSpentTime($cardGet, 290);
// remove spend time
$cardGet->stopwatch = null;
$client->card->update($cardGet);

// get history action by card
$client->cardAction->getActions($cardGet->id);

// upload attachment to card
try {
    $attachment = $client->attachment->upload($cardGet->id, $filePath);
} catch (Throwable $exception) {
    die('Upload attachment to card error');
}

// update name by attachment
$client->attachment->updateName($attachment->id, 'mimo');

// delete attachment
$client->attachment->delete($attachment->id);

// add tasks
$client->cardTask->create($cardGet->id, 'one', 1);
$taskItem = $client->cardTask->create($cardGet->id, 'two', 2);
$taskItem->isCompleted = true;
$client->cardTask->update($taskItem);

$boardGet = $client->board->get($boardGet->item->id);

// get card tasks by board cards and update it
foreach ($boardGet->included->tasks as $task) {
    $task->isCompleted = true;
    $client->cardTask->update($task);
}

$boardGet = $client->board->get($boardGet->item->id);

// delete atomicity tasks
foreach ($boardGet->included->tasks as $task) {
    $client->cardTask->delete($task->id);
}

// card member
$boardGet = $client->board->get($boardGet->item->id);
$user = $boardGet->included->users[0];
$member = $client->cardMembership->add($cardGet->id, $user->id);
// remove member
$client->cardMembership->remove($cardGet->id, $user->id);

// card due Date
$cardGet->dueDate = (new DateTimeImmutable())->modify('+ 1 year');
$client->card->update($cardGet);
// remove due date
$cardGet->dueDate = null;
$client->card->update($cardGet);

// get notify
$notify = $client->notification->list();
// reading notify - see $client->notification->markIsRead([$notifyId, $notifyId])

// add label
$label = $client->label->create($boardGet->item->id, 'test', LabelColorEnum::APRICOT_RED, 1);

// update label
$client->label->update($label->id, 'mimo', LabelColorEnum::CORAL_GREEN);

// add label to card
$client->cardLabel->add($cardGet->id, $label->id);
$client->cardLabel->remove($cardGet->id, $label->id);

// delete label
$client->label->delete($label->id);

// delete card
$client->card->delete($cardGet->id);

// delete board
$client->board->delete($boardGet->item->id);

// delete project
$client->project->delete($project->id);