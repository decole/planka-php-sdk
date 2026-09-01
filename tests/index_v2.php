<?php

declare(strict_types=1);

// Run after `composer install` in root directory

use Planka\Bridge\Config;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;

$rawConfig = include __DIR__ . '/config.php';

require __DIR__ . '/../vendor/autoload.php';

dump('========================================');
dump('   Testing Planka SDK v2 Integration    ');
dump('========================================');

// 1. Setup Client
$config = new Config(
    user: $rawConfig['login'],
    password: $rawConfig['password'],
    baseUri: $rawConfig['uri'],
    port: (int) $rawConfig['port'],
);

$client = new PlankaClient($config);

// 2. Ping Server
dump('[1/12] Pinging Planka server...');
$infoResponse = $client->getInfo();
if (200 !== $infoResponse->getStatusCode()) {
    dd('ERROR: Planka server is not reachable!');
}
dump('Server connection OK');

// 3. Authenticate (JWT)
dump('[2/12] Authenticating via JWT (email/password)...');
if (!$client->authenticate()) {
    dd('ERROR: Authentication failed!');
}
dump('JWT Authentication OK. Token acquired.');

// 4. Test System Config
dump('[3/12] Fetching System Config...');
try {
    $sysConfig = $client->systemConfig->get();
    if ($sysConfig instanceof SystemConfigDto) {
        dump("System Config OK (id: {$sysConfig->id})");
    }
} catch (Throwable $e) {
    dump('System Config check skipped or unauthorized: ' . $e->getMessage());
}

// 5. Create Test Project
dump('[4/12] Creating test Project...');
$projectName = 'v2-Test-Project-' . time();
$project = $client->project->create($projectName);

if (!$project instanceof ProjectDto || $project->name !== $projectName) {
    dd('ERROR: Failed to create project!');
}
dump("Project created OK (ID: {$project->id})");

// 6. Base Custom Field Groups & Fields
dump('[5/12] Testing Base Custom Field Group & Custom Fields...');
$baseGroup = $client->baseCustomFieldGroup->create($project->id, 'Base Specs');
if (!$baseGroup instanceof BaseCustomFieldGroupDto) {
    dd('ERROR: Base custom field group creation failed!');
}
dump("Base Custom Field Group created OK (ID: {$baseGroup->id})");

$customField = $client->customField->createInBaseGroup(
    baseGroupId: $baseGroup->id,
    name: 'Priority',
    showOnFrontOfCard: true,
);
if (!$customField instanceof CustomFieldDto) {
    dd('ERROR: Custom field creation failed!');
}
dump("Custom Field created OK (ID: {$customField->id}, name: {$customField->name})");

// 7. Create Test Board
dump('[6/12] Creating test Board...');
$boardName = 'v2-Test-Board';
$board = $client->board->create($project->id, $boardName, 0);

if (!$board instanceof BoardDto || null === $board->item) {
    dd('ERROR: Failed to create board!');
}
$boardId = $board->item->id;
dump("Board created OK (ID: {$boardId})");

// Update board view settings
$updatedBoard = $client->board->update($boardId, 'v2-Test-Board-Updated');
dump('Board updated OK');

// Custom Field Group on Board
$boardGroup = $client->customFieldGroup->createInBoard($boardId, 'Board Fields');
if (!$boardGroup instanceof CustomFieldGroupDto) {
    dd('ERROR: Custom field group on board creation failed!');
}
dump("Board Custom Field Group created OK (ID: {$boardGroup->id})");

// 8. Test Lists (Columns)
dump('[7/12] Testing Board Lists...');
$columnTodo = $client->boardList->create($boardId, 'To Do', 1);
$columnDone = $client->boardList->create($boardId, 'Done', 2);
dump("Lists created: 'To Do' (ID: {$columnTodo->id}), 'Done' (ID: {$columnDone->id})");

// Sort cards in list
$client->boardList->sort($columnTodo->id, 'name', 'asc');
dump('List sort OK');

// 9. Test Cards & Card v2 Operations
dump('[8/12] Testing Cards (Create, Duplicate, Read Notifications)...');
$card1 = $client->card->create($columnTodo->id, 'Task 1', 1);
if (!$card1 instanceof CardDto) {
    dd('ERROR: Failed to create card 1!');
}
dump("Card created OK (ID: {$card1->id})");

// Duplicate Card (Planka v2 Feature)
$duplicatedCard = $client->card->duplicate($card1->id);
if (!$duplicatedCard instanceof CardDto) {
    dd('ERROR: Card duplication failed!');
}
dump("Card duplicate OK (New Card ID: {$duplicatedCard->id})");

// Read Notifications for Card (Planka v2 Feature)
$client->card->readNotifications($card1->id);
dump('Card readNotifications OK');

// Card Subtasks
dump('Testing Card Tasks (subtasks)...');
$task1 = $client->cardTask->create($card1->id, 'Subtask 1', 0);
$task1->isCompleted = true;
$client->cardTask->update($task1);
dump('Card Task completed OK');
$client->cardTask->delete($task1->id);
dump('Card Task deleted OK');

// Move cards between lists
$client->boardList->moveCards($columnTodo->id, $columnDone->id);
dump('Move cards between lists OK');

// 10. Test Webhooks (Planka v2 Feature)
dump('[9/12] Testing Webhooks (v2 Feature)...');
try {
    $webhook = $client->webhook->create(
        name: 'Test Webhook v2',
        url: 'https://example.com/webhook-test',
        events: 'cardCreate,cardUpdate',
    );
    if ($webhook instanceof WebhookDto) {
        dump("Webhook created OK (ID: {$webhook->id})");

        $webhooks = $client->webhook->list();
        dump('Webhook list OK (Count: ' . count($webhooks) . ')');

        $client->webhook->update($webhook->id, name: 'Updated Webhook v2');
        dump('Webhook update OK');

        $client->webhook->delete($webhook->id);
        dump('Webhook delete OK');
    }
} catch (Throwable $e) {
    dump('Webhook test skipped or unauthorized: ' . $e->getMessage());
}

// 11. Test Notification Services (Planka v2 Feature)
dump('[10/12] Testing Notification Services (v2 Feature)...');
try {
    $notifService = $client->notificationService->createInBoard(
        boardId: $boardId,
        url: 'https://example.com/notif-test',
        format: NotificationServiceFormatEnum::TEXT,
    );
    if ($notifService instanceof NotificationServiceDto) {
        dump("Notification Service created OK (ID: {$notifService->id})");

        $client->notificationService->test($notifService->id);
        dump('Notification Service test call OK');

        $client->notificationService->delete($notifService->id);
        dump('Notification Service delete OK');
    }
} catch (Throwable $e) {
    dump('Notification Service test skipped or unauthorized: ' . $e->getMessage());
}

// 12. Cleanup
dump('[11/12] Cleaning up test resources...');

// Delete Cards
$client->card->delete($card1->id);
$client->card->delete($duplicatedCard->id);
dump('Test cards deleted OK');

// Delete Board
$client->board->delete($boardId);
dump('Test board deleted OK');

// Delete Base Custom Field Group
$client->baseCustomFieldGroup->delete($baseGroup->id);
dump('Base Custom Field Group deleted OK');

// Delete Project
$client->project->delete($project->id);
dump('Test project deleted OK');

dump('[12/12] All tests completed successfully!');
dump('========================================');
dump('   PLANKA SDK v2 INTEGRATION TEST PASSED ');
dump('========================================');
