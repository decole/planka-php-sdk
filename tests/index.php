<?php

declare(strict_types=1);

/**
 * Tests for Planka v.2.
 */

// Run after `composer install` in root directory

use Planka\Bridge\Config;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Views\Dto\Card\TaskListDto;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;
use Symfony\Component\HttpClient\Exception\ClientException;

$rawConfig = include __DIR__ . '/config.php';

require __DIR__ . '/../vendor/autoload.php';

dump('========================================');
dump('   Testing Planka SDK v2 Integration    ');
dump('========================================');

// Entity Tracker to prevent deleting user's real projects/boards/cards
$createdTracker = [
    'projects' => [],
    'boards' => [],
    'lists' => [],
    'cards' => [],
    'taskLists' => [],
    'tasks' => [],
    'baseCustomGroups' => [],
    'customGroups' => [],
    'customFields' => [],
    'webhooks' => [],
    'notificationServices' => [],
];

function assertCreated(string $type, string $id, object $dto, array &$tracker): void
{
    $tracker[$type][$id] = $dto;
}

function safeVerifyOwned(string $type, string $id, array &$tracker): void
{
    if (!isset($tracker[$type][$id])) {
        dd("SECURITY GUARD TRIGGERED: Attempted to delete or modify {$type} (ID: {$id}) that was NOT created by this test run!");
    }
}

function inspectDto(object $dto, string $label): void
{
    dump("--- INSPECTING DTO: {$label} ---");
    dump($dto);

    if (property_exists($dto, '_rawResponse') && !empty($dto->_rawResponse)) {
        dump("--- RAW RESPONSE FOR: {$label} ---");
        dump($dto->_rawResponse);
    }
}

// 1. Setup Client
$config = new Config(
    user: $rawConfig['login'],
    password: $rawConfig['password'],
    baseUri: $rawConfig['uri'],
    port: (int) $rawConfig['port'],
);

$client = new PlankaClient($config);

// 2. Ping Server
dump('[1/13] Pinging Planka server...');
$infoResponse = $client->getInfo();

if (200 !== $infoResponse->getStatusCode()) {
    dd('ERROR: Planka server is not reachable!');
}

dump('Server connection OK');

// 3. Authenticate (JWT)
dump('[2/13] Authenticating via JWT (email/password)...');

if (!$client->authenticate()) {
    dd('ERROR: Authentication failed!');
}

dump('JWT Authentication OK. Token acquired.');

// 4. Test System Config
dump('[3/13] Fetching System Config...');

try {
    $sysConfig = $client->systemConfig->get();

    if ($sysConfig instanceof SystemConfigDto) {
        inspectDto($sysConfig, 'SystemConfigDto');
    }
} catch (Throwable $e) {
    dump('System Config check skipped or unauthorized: ' . $e->getMessage());
}

// 5. Create Test Project
dump('[4/13] Creating test Project...');
$projectName = '[v2-test-DO-NOT-TOUCH] Project-' . time();
$project = $client->project->create($projectName);

if (!$project instanceof ProjectDto || $project->name !== $projectName) {
    dd('ERROR: Failed to create project!');
}

assertCreated('projects', $project->id, $project, $createdTracker);
inspectDto($project, 'ProjectDto (created)');

// 6. Base Custom Field Groups & Fields
dump('[5/13] Testing Base Custom Field Group & Custom Fields...');
$baseGroup = $client->baseCustomFieldGroup->create($project->id, 'Base Specs');

if (!$baseGroup instanceof BaseCustomFieldGroupDto) {
    dd('ERROR: Base custom field group creation failed!');
}

assertCreated('baseCustomGroups', $baseGroup->id, $baseGroup, $createdTracker);
inspectDto($baseGroup, 'BaseCustomFieldGroupDto');

$customField = $client->customField->createInBaseGroup(
    baseGroupId: $baseGroup->id,
    name: 'Priority',
    showOnFrontOfCard: true,
);

if (!$customField instanceof CustomFieldDto) {
    dd('ERROR: Custom field creation failed!');
}

assertCreated('customFields', $customField->id, $customField, $createdTracker);
inspectDto($customField, 'CustomFieldDto');

// 7. Create Test Board
dump('[6/13] Creating test Board...');
$boardName = '[v2-test-DO-NOT-TOUCH] Board';
$board = $client->board->create($project->id, $boardName, 0);

if (!$board instanceof BoardDto || null === $board->item) {
    dd('ERROR: Failed to create board!');
}

$boardId = $board->item->id;
assertCreated('boards', $boardId, $board, $createdTracker);
inspectDto($board, 'BoardDto');

// Update board view settings
$updatedBoard = $client->board->update($boardId, '[v2-test-DO-NOT-TOUCH] Board-Updated');
inspectDto($updatedBoard, 'BoardDto (updated)');

// Custom Field Group on Board
$boardGroup = $client->customFieldGroup->createInBoard($boardId, 'Board Fields');

if (!$boardGroup instanceof CustomFieldGroupDto) {
    dd('ERROR: Custom field group on board creation failed!');
}

assertCreated('customGroups', $boardGroup->id, $boardGroup, $createdTracker);
inspectDto($boardGroup, 'CustomFieldGroupDto');

// 8. Test Lists (Columns)
dump('[7/13] Testing Board Lists (Create, Sort, Delete)...');
$columnTodo = $client->boardList->create($boardId, '[v2-test] To Do', 1);
$columnDone = $client->boardList->create($boardId, '[v2-test] Done', 2);
$columnTemp = $client->boardList->create($boardId, '[v2-test] Temporary Column', 3);

assertCreated('lists', $columnTodo->id, $columnTodo, $createdTracker);
assertCreated('lists', $columnDone->id, $columnDone, $createdTracker);
assertCreated('lists', $columnTemp->id, $columnTemp, $createdTracker);

inspectDto($columnTodo, 'BoardListDto (To Do)');

// Sort cards in list
$client->boardList->sort($columnTodo->id, 'name', 'asc');
dump('List sort OK');

// Test Column Deletion
dump('Testing Column Deletion...');
safeVerifyOwned('lists', $columnTemp->id, $createdTracker);
$client->boardList->delete($columnTemp->id);
unset($createdTracker['lists'][$columnTemp->id]);

try {
    $client->boardList->update($columnTemp->id, 'Should Fail');
    dd('ERROR: Column was not deleted on server!');
} catch (ClientException $e) {
    dump('Column deletion verified OK (HTTP error caught on update)');
}

// 9. Test Cards & Card v2 Operations
dump('[8/13] Testing Cards (Create, Duplicate, Read Notifications)...');
$card1 = $client->card->create($columnTodo->id, '[v2-test] Task 1', 1);

if (!$card1 instanceof CardDto) {
    dd('ERROR: Failed to create card 1!');
}

assertCreated('cards', $card1->id, $card1, $createdTracker);
inspectDto($card1, 'CardDto');

// Duplicate Card (Planka v2 Feature)
$duplicatedCard = $client->card->duplicate($card1->id);

if (!$duplicatedCard instanceof CardDto) {
    dd('ERROR: Card duplication failed!');
}

assertCreated('cards', $duplicatedCard->id, $duplicatedCard, $createdTracker);
inspectDto($duplicatedCard, 'CardDto (duplicated)');

// Read Notifications for Card (Planka v2 Feature)
$client->card->readNotifications($card1->id);
dump('Card readNotifications OK');

// 10. Card Tasks & Task Lists
dump('[9/13] Testing Task Lists & Card Tasks (Create, Update, Delete)...');
$taskList = $client->cardTask->createTaskList($card1->id, '[v2-test] Checklist');

if (!$taskList instanceof TaskListDto) {
    dd('ERROR: Task list creation failed!');
}

assertCreated('taskLists', $taskList->id, $taskList, $createdTracker);
inspectDto($taskList, 'TaskListDto');

$task1 = $client->cardTask->create($taskList->id, '[v2-test] Subtask 1', 0);

if (!$task1 instanceof CardTaskDto) {
    dd('ERROR: Card task creation failed!');
}

assertCreated('tasks', $task1->id, $task1, $createdTracker);
inspectDto($task1, 'CardTaskDto');

$task1->isCompleted = true;
$client->cardTask->update($task1);
dump('Card Task update (completed) OK');

// Explicit Task Deletion Test
safeVerifyOwned('tasks', $task1->id, $createdTracker);
$client->cardTask->delete($task1->id);
unset($createdTracker['tasks'][$task1->id]);
dump('Explicit Card Task deletion OK');

// Explicit Task List Deletion Test
safeVerifyOwned('taskLists', $taskList->id, $createdTracker);
$client->cardTask->deleteTaskList($taskList->id);
unset($createdTracker['taskLists'][$taskList->id]);
dump('Explicit Task List deletion OK');

// Move cards between lists (requires closed source list)
try {
    $client->boardList->moveCards($columnTodo->id, $columnDone->id);
    dump('Move cards between lists OK');
} catch (Throwable $e) {
    dump('Move cards note: ' . $e->getMessage());
}

// 11. Test Webhooks (Planka v2 Feature)
dump('[10/13] Testing Webhooks (v2 Feature)...');

try {
    $webhook = $client->webhook->create(
        name: 'Test Webhook v2',
        url: 'https://example.com/webhook-test',
        events: 'cardCreate,cardUpdate',
    );

    if ($webhook instanceof WebhookDto) {
        assertCreated('webhooks', $webhook->id, $webhook, $createdTracker);
        inspectDto($webhook, 'WebhookDto');

        $webhooks = $client->webhook->list();
        dump('Webhook list OK (Count: ' . count($webhooks) . ')');

        $client->webhook->update($webhook->id, name: 'Updated Webhook v2');
        dump('Webhook update OK');

        safeVerifyOwned('webhooks', $webhook->id, $createdTracker);
        $client->webhook->delete($webhook->id);
        unset($createdTracker['webhooks'][$webhook->id]);
        dump('Webhook delete OK');
    }
} catch (Throwable $e) {
    dump('Webhook test skipped or unauthorized: ' . $e->getMessage());
}

// 12. Test Notification Services (Planka v2 Feature)
dump('[11/13] Testing Notification Services (v2 Feature)...');

try {
    $notifService = $client->notificationService->createInBoard(
        boardId: $boardId,
        url: 'https://example.com/notif-test',
        format: NotificationServiceFormatEnum::TEXT,
    );

    if ($notifService instanceof NotificationServiceDto) {
        assertCreated('notificationServices', $notifService->id, $notifService, $createdTracker);
        inspectDto($notifService, 'NotificationServiceDto');

        $client->notificationService->test($notifService->id);
        dump('Notification Service test call OK');

        safeVerifyOwned('notificationServices', $notifService->id, $createdTracker);
        $client->notificationService->delete($notifService->id);
        unset($createdTracker['notificationServices'][$notifService->id]);
        dump('Notification Service delete OK');
    }
} catch (Throwable $e) {
    dump('Notification Service test skipped or unauthorized: ' . $e->getMessage());
}

// 13. Explicit Deletion & Safety Verification
dump('[12/13] Testing Explicit Deletion of Cards, Board, Custom Field Groups, and Project...');

// 1. Delete Cards & Verify 404
safeVerifyOwned('cards', $card1->id, $createdTracker);
$client->card->delete($card1->id);
unset($createdTracker['cards'][$card1->id]);

try {
    $client->card->get($card1->id);
    dd('ERROR: Card1 was not deleted on server!');
} catch (ClientException $e) {
    dump('Card1 deletion verified OK (404 caught)');
}

safeVerifyOwned('cards', $duplicatedCard->id, $createdTracker);
$client->card->delete($duplicatedCard->id);
unset($createdTracker['cards'][$duplicatedCard->id]);

try {
    $client->card->get($duplicatedCard->id);
    dd('ERROR: Duplicated card was not deleted on server!');
} catch (ClientException $e) {
    dump('Duplicated card deletion verified OK (404 caught)');
}

// 2. Delete Board & Verify 404
safeVerifyOwned('boards', $boardId, $createdTracker);
$client->board->delete($boardId);
unset($createdTracker['boards'][$boardId]);

// Board deletion cascades child lists and board custom field groups
unset($createdTracker['lists'][$columnTodo->id], $createdTracker['lists'][$columnDone->id], $createdTracker['customGroups'][$boardGroup->id]);

try {
    $client->board->get($boardId);
    dd('ERROR: Board was not deleted on server!');
} catch (ClientException $e) {
    dump('Board deletion verified OK (404 caught)');
}

// 3. Delete Base Custom Field Group
safeVerifyOwned('baseCustomGroups', $baseGroup->id, $createdTracker);
$client->baseCustomFieldGroup->delete($baseGroup->id);
unset($createdTracker['baseCustomGroups'][$baseGroup->id], $createdTracker['customFields'][$customField->id]);
dump('Base Custom Field Group deletion OK');

// 4. Delete Project & Verify 404
safeVerifyOwned('projects', $project->id, $createdTracker);
$client->project->delete($project->id);
unset($createdTracker['projects'][$project->id]);

try {
    $client->project->get($project->id);
    dd('ERROR: Project was not deleted on server!');
} catch (ClientException $e) {
    dump('Project deletion verified OK (404 caught)');
}

dump('[13/13] Checking tracker state after cleanup...');
$remainingItems = array_sum(array_map('count', $createdTracker));

if ($remainingItems > 0) {
    dd('WARNING: Some tracked test resources were not cleaned up!', $createdTracker);
}

dump('Tracker clean: 0 remaining items');

dump('========================================');
dump('   PLANKA SDK v2 INTEGRATION TEST PASSED ');
dump('========================================');
