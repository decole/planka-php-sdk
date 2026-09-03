<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Card\TaskListDto;

final class TaskListEndpointsTest extends AbstractUnitTestCase
{
    public function testGetTaskList(): void
    {
        $client = $this->createMockClient('CardTask/task_list_create.json');
        $taskList = $client->cardTask->getTaskList('1854744333501072952');

        $this->assertInstanceOf(TaskListDto::class, $taskList);
        $this->assertNotEmpty($taskList->id);
    }

    public function testUpdateTaskList(): void
    {
        $client = $this->createMockClient('CardTask/task_list_create.json');
        $taskList = $client->cardTask->updateTaskList('1854744333501072952', name: 'Updated Checklist');

        $this->assertInstanceOf(TaskListDto::class, $taskList);
        $this->assertNotEmpty($taskList->id);
    }
}
