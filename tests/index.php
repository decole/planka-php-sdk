<?php

declare(strict_types=1);

// before run `composer install on root directory`

use Planka\Bridge\Config;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Background\BackgroundDto;
use Planka\Bridge\Views\Dto\Card\StopWatchDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use Planka\Bridge\Views\Factory\Project\ProjectManagerDto;

// copy config.example.php to config.php and setup for you
$config = include __DIR__ . '/config.php';

require __DIR__ . '/../vendor/autoload.php';

// Diff raw sata by DTO result.
function bin(array $array, ?array $raw, string $path = ''): void
{
    foreach ($array as $key => $item) {
        if ($key === '_rawResponse') {
            continue;
        }

        $rawElement = is_array($raw) ? $raw[$key] : $raw;

        if (is_array($item)) {
            if (($item['date'] ?? null) !== null) {
                $date = $item['date'];
                $dateTime = new DateTime($date);
                $rawDateTime = new DateTime($rawElement);

                if ($dateTime != $rawDateTime) {
                    dump('date not corrected!', $dateTime, $rawDateTime);
                }

                continue;
            }

            bin($item, $rawElement, $path . '.' . $key);
        } else {
            $dtoItem = $item;

            if ($dtoItem != $rawElement) {
                dump('is not ok', "key: {$key} of path {$path}", $dtoItem, $rawElement);
            }
        }
    }
}

dump('
===

Testing Planka version 1.24.3

===
');

dump('Start tests');

$config = new Config(
    user: $config['login'],
    password: $config['password'],
    baseUri: $config['uri'],
    port: $config['port'],
);
$client = new PlankaClient($config);

$filePath = __DIR__ . '/image.png';

dump('Configure success');

if (200 !== $client->getInfo()->getStatusCode()) {
    dd('Planka server not connected!');
}

dump('Try authenticate');

if (!$client->authenticate()) {
    dd('User credentials not corrected!');
}

dump('Start check routes');

dump('
--- --- ---
');

/////////////// TODO убрать, когда сделаю тесты до конца

$project = $client->project->get('1437009275900659095');

///////////////




//dump('Get projects data');
//
//dump('GET /api/projects');
//
//$list = $client->project->list();
//
//dump('check diff raw data by DTO result');
//$array = json_decode(json_encode($list), true);
//bin($array, $list->_rawResponse);
//dump('finish checking diff raw data by DTO result');
//dump('
//--- --- ---
//');

//dump('Create projects');
//
//dump('POST /api/projects');
//
//$project = $client->project->create('test');
//
//if ($project->name !== 'test') {
//    dd('project name not corrected!');
//}
//dump('create project successfuly');

//dump('try rename');
//$projectGet = $client->project->get($project->id);
//$projectGet->name = 'trim';
//$client->project->update($projectGet);
//
//$project = $client->project->get($project->id);
//
//if ($project->name !== 'trim') {
//    dd('project name not corrected! Has no `trim` name');
//}
//
//dump('Check upload image as background');
//try {
//    $projectWithImage = $client->project->updateBackgroundImage($project->id, $filePath);
//} catch (Throwable $exception) {
//    dd('Upload image to project error');
//}

//if ($project->background === null ||
//    $project->background->type != BackgroundTypeEnum::IMAGE ||
//    $project->backgroundImage === null ||
//    $project->backgroundImage->coverUrl === null ||
//    $project->backgroundImage->url === null
//) {
//    dd('background image not corrected!', $project);
//}
//
//dump('background image functionality is ok');

//dump('delete project background image');
//$project->backgroundImage = null;
//$project->background = null;
//$project = $client->project->update($project);
//
//if ($project->background !== null || $project->backgroundImage !== null) {
//    dd('cant delete background image', $project);
//}
//dump('delete project background image successfully');

//dump('Set project background as gradient');
//$project->background = new BackgroundDto(
//    type: BackgroundTypeEnum::GRADIENT,
//    name: BackgroundGradientEnum::ALGAE_GREEN,
//);
//$project = $client->project->update($project);
//
//if ($project->background === null ||
//    $project->background->type !== BackgroundTypeEnum::GRADIENT ||
//    $project->background->name !== BackgroundGradientEnum::ALGAE_GREEN ||
//    $project->backgroundImage !== null
//) {
//    dd('background image not corrected!', $project);
//}
//dump('Set project background as gradient - success');

//dump('Add user to projectManager');
//$user = null;
//
//$users = $client->user->list();
//
///** @var UserDto $item */
//foreach ($users as $item) {
//    $user = $item;
//}
//
//if ($user === null) {
//    dd('User not found');
//}
//
//$manager = $client->projectManager->add(
//    projectId: $project->id,
//    userId: $user->id,
//);
//
//if (!$manager instanceof ProjectManagerDto) {
//    dd('Project manager not created!');
//}
//
//if ($manager->userId !== $user->id) {
//    dd('User id not corrected!');
//}
//
//try {
//    $client->projectManager->add(
//        projectId: $project->id,
//        userId: $user->id,
//    );
//
//    dd('Detect duble create projectManager');
//} catch (Throwable $e) {
//}
//
//dump('Add projectManager successfuly');
//
//dump('Delete projectManager');
//$manager = $client->projectManager->remove($manager->id);
//
//try {
//    $manager = $client->projectManager->remove($manager->id);
//
//    dd('Detect duble delete projectManager');
//} catch (Throwable $e) {
//}
//dump('Delete projectManager successfuly');

//dump('Check user functionality');
//$users = $client->user->list();
//
//foreach ($users as $user) {
//    $array = json_decode(json_encode($user), true);
//    bin($array, $user->_rawResponse);
//}
//dump('Check user functionality successful');

//dump('Create user');
//
//$currentPassword = '!@#(ASDFkrw_';
//$mewPassword = 'balanda123@@#!_=';
//$currentEmail = 'test@example.com';
//$newEmail = 'semiLTS@ll.com';

//$userNew = $client->user->create(
//    email: $currentEmail,
//    name: 'Test User',
//    password: $currentPassword,
//    username: 'test',
//);
//
//dump("new user id - {$userNew->id}");
//
//if ($userNew->email !== $currentEmail ||
//    $userNew->name !== 'Test User' ||
//    $userNew->username !== 'test'
//) {
//    dd('Created user not corrected');
//}
//
//$user = $client->user->get($userNew->id);
//
//if ($user->email !== $currentEmail ||
//    $user->name !== 'Test User' ||
//    $user->username !== 'test'
//) {
//    dd('Created user not corrected');
//}
//
//$userNew = null;

//$user->username = 'Trelome';
//
//$user = $client->user->get($user->id);
//
//if ($user->username !== mb_strtolower('Trelome')) {
//    dd('Change username not corrected');
//}
//
//$user->email = $newEmail;
//$client->user->updateEmail($user);
//
//$user = $client->user->get($user->id);
//
//if ($user->email != mb_strtolower($newEmail)) {
//    dd('Change email not corrected');
//}
//
//try {
//    $client->user->updatePassword(id: $user->id, current: $currentPassword, new: $mewPassword);
//} catch (\Throwable $exception) {
//    dd('change passwprd not corrected');
//}
//
//try {
//    $client->user->updateAvatar($user, $filePath);
//} catch (\Throwable $exception) {
//    dd('change avatar not corrected');
//}
//
//$user = $client->user->get($user->id);
//
//if ($user->avatarUrl === null) {
//    dd('test changing avatar not corrected');
//}
//
//$name = $user->name = 'test';
//$username = $user->username = 'test user name';
//$organization = $user->organization = 'test organization';
//$phone = $user->phone = '+12312+3+1+23';
//
//$client->user->update($user);
//
//$user = $client->user->get($user->id);
//
//if ($user->name !== $name || $user->organization !== $organization || $user->phone !== $phone) {
//    dd('change user name, organistion, phone - not corrected');
//}
//
//$user = $client->user->get($user->id);
//$user->isAdmin = true;
//$user = $client->user->update($user);
//
//if ($user->isAdmin !== true) {
//    dd('change user is admin not corrected');
//}
//
//$user = $client->user->get($user->id);
//
//if ($user->isAdmin !== true) {
//    dd('change user is admin not corrected');
//}
//
//$user->isAdmin = false;
//$user = $client->user->update($user);
//
//if ($user->isAdmin !== false) {
//    dd('change user is admin to false not corrected');
//}
//
//$user = $client->user->get($user->id);
//
//if ($user->isAdmin !== false) {
//    dd('change user is admin to false not corrected');
//}
//
//$client->user->delete($user);
//try {
//    $user = $client->user->get($user->id);
//
//    dd('user not deleted');
//} catch (\Throwable $exception) {
//}

//dump('User functionality check - success');

dd('---');



// add board
$board = $client->board->create($projectGet->id, 'testCard', 1);
$boardGet = $client->board->get($board->item->id);
$boardOther = $client->board->create($projectGet->id, 'archive', 2);

$client->board->update($boardGet->item->id, 'romb');

// add board list
$list = $client->boardList->create($boardGet->item->id, 'one', 1);
$listOther = $client->boardList->create($boardOther->item->id, 'archive', 22);

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


// start timer on card
$client->card->triggerTimer($card, true);

// stop timer on card
$client->card->triggerTimer($card, false);

// test moving to other board
$card->boardId = $boardOther->item->id;
$card->listId = $listOther->id;
$card->position = 33;
$client->card->moveCard($card);

$card->boardId = $board->item->id;
$card->listId = $list->id;
$card->position = 1;
$client->card->moveCard($card);

// add spend worked time to card
$client->card->addSpentTime($cardGet, 290);

// remove spend time
$client->card->clearTime($cardGet);

// get history action by card
$client->cardAction->getActions($cardGet->id);

// upload attachment to card
try {
    $attachment = $client->attachment->upload($cardGet->id, $filePath);
} catch (Throwable $exception) {
    exit('Upload attachment to card error');
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
