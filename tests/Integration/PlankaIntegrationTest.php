<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Integration;

use PHPUnit\Framework\TestCase;
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

final class PlankaIntegrationTest extends TestCase
{
    private PlankaClient $client;

    private array $createdTracker = [
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

    protected function setUp(): void
    {
        $configFile = __DIR__ . '/../config.php';

        if (!file_exists($configFile)) {
            $this->markTestSkipped('Integration tests skipped: tests/config.php not found. Copy config.example.php to config.php and setup copied config!');
        }

        $rawConfig = include $configFile;

        $config = new Config(
            user: $rawConfig['login'],
            password: $rawConfig['password'],
            baseUri: $rawConfig['uri'],
            port: (int) $rawConfig['port'],
        );

        $this->client = new PlankaClient($config);
    }

    private function assertCreated(string $type, string $id, object $dto): void
    {
        $this->createdTracker[$type][$id] = $dto;
    }

    private function safeVerifyOwned(string $type, string $id): void
    {
        if (!isset($this->createdTracker[$type][$id])) {
            $this->fail("SECURITY GUARD: Attempted to delete or modify {$type} (ID: {$id}) that was NOT created by this test run!");
        }
    }

    public function testFullIntegrationLifecycle(): void
    {
        // 1. Ping Server & Terms
        $infoResponse = $this->client->getInfo();
        $this->assertEquals(200, $infoResponse->getStatusCode(), 'Planka server is not reachable!');

        try {
            $terms = $this->client->getTerms();
            $this->assertIsArray($terms);
        } catch (\Throwable $e) {
            // Terms optional
        }

        // 2. Authenticate
        $authenticated = $this->client->authenticate();
        $this->assertTrue($authenticated, 'Authentication failed!');

        // 3. System Config
        try {
            $sysConfig = $this->client->systemConfig->get();

            if ($sysConfig instanceof SystemConfigDto) {
                $this->assertNotEmpty($sysConfig->id);
            }
        } catch (\Throwable $e) {
            // System Config optional check
        }

        // 4. Create Project
        $projectName = '[v2-test-DO-NOT-TOUCH] Project-' . time();
        $project = $this->client->project->create($projectName);

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertEquals($projectName, $project->name);
        $this->assertCreated('projects', $project->id, $project);

        // 5. Base Custom Field Groups & Fields
        $baseGroup = $this->client->baseCustomFieldGroup->create($project->id, 'Base Specs');
        $this->assertInstanceOf(BaseCustomFieldGroupDto::class, $baseGroup);
        $this->assertCreated('baseCustomGroups', $baseGroup->id, $baseGroup);

        $customField = $this->client->customField->createInBaseGroup(
            baseGroupId: $baseGroup->id,
            name: 'Priority',
            showOnFrontOfCard: true,
        );
        $this->assertInstanceOf(CustomFieldDto::class, $customField);
        $this->assertCreated('customFields', $customField->id, $customField);

        // 6. Create Board
        $boardName = '[v2-test-DO-NOT-TOUCH] Board';
        $board = $this->client->board->create($project->id, $boardName, 0);

        $this->assertInstanceOf(BoardDto::class, $board);
        $this->assertNotNull($board->item);
        $boardId = $board->item->id;
        $this->assertCreated('boards', $boardId, $board);

        // Update board
        $updatedBoard = $this->client->board->update($boardId, '[v2-test-DO-NOT-TOUCH] Board-Updated');
        $this->assertInstanceOf(BoardDto::class, $updatedBoard);

        // Custom Field Group on Board
        $boardGroup = $this->client->customFieldGroup->createInBoard($boardId, 'Board Fields');
        $this->assertInstanceOf(CustomFieldGroupDto::class, $boardGroup);
        $this->assertCreated('customGroups', $boardGroup->id, $boardGroup);

        // 7. Test Lists (Columns)
        $columnTodo = $this->client->boardList->create($boardId, '[v2-test] To Do', 1);
        $columnDone = $this->client->boardList->create($boardId, '[v2-test] Done', 2);
        $columnTemp = $this->client->boardList->create($boardId, '[v2-test] Temporary Column', 3);

        $this->assertCreated('lists', $columnTodo->id, $columnTodo);
        $this->assertCreated('lists', $columnDone->id, $columnDone);
        $this->assertCreated('lists', $columnTemp->id, $columnTemp);

        // Sort cards in list
        $this->client->boardList->sort($columnTodo->id, 'name', 'asc');

        // Test Column Deletion
        $this->safeVerifyOwned('lists', $columnTemp->id);
        $this->client->boardList->delete($columnTemp->id);
        unset($this->createdTracker['lists'][$columnTemp->id]);

        try {
            $this->client->boardList->update($columnTemp->id, 'Should Fail');
            $this->fail('ERROR: Column was not deleted on server!');
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }

        // 8. Test Cards & Card v2 Operations
        $card1 = $this->client->card->create($columnTodo->id, '[v2-test] Task 1', 1);
        $this->assertInstanceOf(CardDto::class, $card1);
        $this->assertCreated('cards', $card1->id, $card1);

        $duplicatedCard = $this->client->card->duplicate($card1->id);
        $this->assertInstanceOf(CardDto::class, $duplicatedCard);
        $this->assertCreated('cards', $duplicatedCard->id, $duplicatedCard);

        $this->client->card->readNotifications($card1->id);

        // 9. Card Tasks & Task Lists
        $taskList = $this->client->cardTask->createTaskList($card1->id, '[v2-test] Checklist');
        $this->assertInstanceOf(TaskListDto::class, $taskList);
        $this->assertCreated('taskLists', $taskList->id, $taskList);

        $fetchedTaskList = $this->client->cardTask->getTaskList($taskList->id);
        $this->assertInstanceOf(TaskListDto::class, $fetchedTaskList);

        $updatedTaskList = $this->client->cardTask->updateTaskList($taskList->id, name: '[v2-test] Checklist Updated');
        $this->assertInstanceOf(TaskListDto::class, $updatedTaskList);

        $task1 = $this->client->cardTask->create($taskList->id, '[v2-test] Subtask 1', 0);
        $this->assertInstanceOf(CardTaskDto::class, $task1);
        $this->assertCreated('tasks', $task1->id, $task1);

        $task1->isCompleted = true;
        $this->client->cardTask->update($task1);

        // Comments lifecycle
        try {
            $comment = $this->client->comment->add($card1->id, '[v2-test] Integration Comment');
            $this->assertNotEmpty($comment->id);

            $commentList = $this->client->comment->list($card1->id);
            $this->assertIsArray($commentList);

            $updatedComment = $this->client->comment->update($comment->id, '[v2-test] Updated Comment');
            $this->assertInstanceOf(\Planka\Bridge\Views\Dto\Comment\CommentDto::class, $updatedComment);

            $this->client->comment->remove($comment->id);
        } catch (\Throwable $e) {
            // Comments optional
        }

        // Board Actions
        try {
            $boardActions = $this->client->cardAction->getBoardActions($boardId);
            $this->assertInstanceOf(\Planka\Bridge\Views\Dto\Card\CardActionListDto::class, $boardActions);
        } catch (\Throwable $e) {
            // Board Actions optional
        }

        $this->safeVerifyOwned('tasks', $task1->id);
        $this->client->cardTask->delete($task1->id);

        unset($this->createdTracker['tasks'][$task1->id]);

        $this->safeVerifyOwned('taskLists', $taskList->id);
        $this->client->cardTask->deleteTaskList($taskList->id);

        unset($this->createdTracker['taskLists'][$taskList->id]);

        // 10. Webhooks
        try {
            $webhook = $this->client->webhook->create(
                name: 'Test Webhook v2',
                url: 'https://example.com/webhook-test',
                events: 'cardCreate,cardUpdate',
            );

            if ($webhook instanceof WebhookDto) {
                $this->assertCreated('webhooks', $webhook->id, $webhook);

                $webhooks = $this->client->webhook->list();
                $this->assertNotEmpty($webhooks);

                $this->client->webhook->update($webhook->id, name: 'Updated Webhook v2');

                $this->safeVerifyOwned('webhooks', $webhook->id);
                $this->client->webhook->delete($webhook->id);

                unset($this->createdTracker['webhooks'][$webhook->id]);
            }
        } catch (\Throwable $e) {
            // Webhook permissions optional
        }

        // 11. Notification Services
        try {
            $notifService = $this->client->notificationService->createInBoard(
                boardId: $boardId,
                url: 'https://example.com/notif-test',
                format: NotificationServiceFormatEnum::TEXT,
            );

            if ($notifService instanceof NotificationServiceDto) {
                $this->assertCreated('notificationServices', $notifService->id, $notifService);

                $this->client->notificationService->test($notifService->id);

                $this->safeVerifyOwned('notificationServices', $notifService->id);
                $this->client->notificationService->delete($notifService->id);

                unset($this->createdTracker['notificationServices'][$notifService->id]);
            }
        } catch (\Throwable $e) {
            // Notification Service optional
        }

        // 12. Explicit Deletion & Safety Verification
        $this->safeVerifyOwned('cards', $card1->id);
        $this->client->card->delete($card1->id);

        unset($this->createdTracker['cards'][$card1->id]);

        try {
            $this->client->card->get($card1->id);
            $this->fail('ERROR: Card1 was not deleted on server!');
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }

        $this->safeVerifyOwned('cards', $duplicatedCard->id);
        $this->client->card->delete($duplicatedCard->id);

        unset($this->createdTracker['cards'][$duplicatedCard->id]);

        try {
            $this->client->card->get($duplicatedCard->id);
            $this->fail('ERROR: Duplicated card was not deleted on server!');
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }

        $this->safeVerifyOwned('boards', $boardId);
        $this->client->board->delete($boardId);

        unset($this->createdTracker['boards'][$boardId]);

        unset(
            $this->createdTracker['lists'][$columnTodo->id],
            $this->createdTracker['lists'][$columnDone->id],
            $this->createdTracker['customGroups'][$boardGroup->id],
        );

        try {
            $this->client->board->get($boardId);
            $this->fail('ERROR: Board was not deleted on server!');
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }

        $this->safeVerifyOwned('baseCustomGroups', $baseGroup->id);
        $this->client->baseCustomFieldGroup->delete($baseGroup->id);

        unset(
            $this->createdTracker['baseCustomGroups'][$baseGroup->id],
            $this->createdTracker['customFields'][$customField->id],
        );

        $this->safeVerifyOwned('projects', $project->id);
        $this->client->project->delete($project->id);

        unset($this->createdTracker['projects'][$project->id]);

        try {
            $this->client->project->get($project->id);
            $this->fail('ERROR: Project was not deleted on server!');
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }

        $remainingItems = array_sum(array_map('count', $this->createdTracker));
        $this->assertEquals(0, $remainingItems, 'Tracker cleanup failed: some test resources remained!');
    }
}
