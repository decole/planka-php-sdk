<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Views\Dto\Card\TaskListDto;

final class CardTaskTest extends AbstractUnitTestCase
{
    public function testCreateTaskList(): void
    {
        $client = $this->createMockClient('CardTask/task_list_create.json');
        $taskList = $client->cardTask->createTaskList('1854744332452496946', 'Checklist');

        $this->assertInstanceOf(TaskListDto::class, $taskList);
        $this->assertNotEmpty($taskList->id);
        $this->assertEquals('[v2-test] Checklist', $taskList->name);
    }

    public function testCreateTask(): void
    {
        $client = $this->createMockClient('CardTask/task_create.json');
        $task = $client->cardTask->create('1854744333501072952', 'Subtask 1', 0);

        $this->assertInstanceOf(CardTaskDto::class, $task);
        $this->assertNotEmpty($task->id);
        $this->assertEquals('[v2-test] Subtask 1', $task->name);
    }
}
