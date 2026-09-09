<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardTask\CardTaskCreateAction;
use Planka\Bridge\Actions\CardTask\CardTaskDeleteAction;
use Planka\Bridge\Actions\CardTask\CardTaskUpdateAction;
use Planka\Bridge\Actions\CardTask\TaskListCreateAction;
use Planka\Bridge\Actions\CardTask\TaskListDeleteAction;
use Planka\Bridge\Actions\CardTask\TaskListUpdateAction;
use Planka\Bridge\Actions\CardTask\TaskListViewAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Views\Dto\Card\TaskListDto;
use Planka\Bridge\Views\Factory\Card\CardTaskDtoFactory;
use Planka\Bridge\Views\Factory\Card\TaskListDtoFactory;

final class CardTask
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /** 'POST /api/cards/:cardId/task-lists' */
    public function createTaskList(string $cardId, string $name, int $position = 65536): TaskListDto
    {
        return $this->client->post(new TaskListCreateAction(
            cardId: $cardId,
            name: $name,
            position: $position,
        ));
    }

    /** 'GET /api/task-lists/:id' */
    public function getTaskList(string $taskListId): TaskListDto
    {
        return $this->client->get(new TaskListViewAction(taskListId: $taskListId));
    }

    /** 'PATCH /api/task-lists/:id' */
    public function updateTaskList(
        string $taskListId,
        ?string $name = null,
        ?int $position = null,
        ?bool $showOnFrontOfCard = null,
        ?bool $hideCompletedTasks = null,
    ): TaskListDto {
        $data = [];

        if (null !== $name) {
            $data['name'] = $name;
        }

        if (null !== $position) {
            $data['position'] = $position;
        }

        if (null !== $showOnFrontOfCard) {
            $data['showOnFrontOfCard'] = $showOnFrontOfCard;
        }

        if (null !== $hideCompletedTasks) {
            $data['hideCompletedTasks'] = $hideCompletedTasks;
        }

        return $this->client->patch(new TaskListUpdateAction(
            taskListId: $taskListId,
            data: $data,
        ));
    }

    /**
     * 'PATCH /api/task-lists/:id' - Partially updates task list properties.
     *
     * @param string $taskListId Task list ID
     * @param array{
     *   name?: string,
     *   position?: int,
     *   showOnFrontOfCard?: bool,
     *   hideCompletedTasks?: bool
     * } $map Associative array of fields to update
     */
    public function patchingTaskList(string $taskListId, array $map): TaskListDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/task-lists/{$taskListId}",
            data: $map,
            hydrateCallback: new TaskListDtoFactory(),
        ));
    }

    /** 'DELETE /api/task-lists/:id' */
    public function deleteTaskList(string $taskListId): TaskListDto
    {
        return $this->client->delete(new TaskListDeleteAction(taskListId: $taskListId));
    }

    /** 'POST /api/task-lists/:taskListId/tasks' */
    public function create(string $taskListId, string $name, int $position = 65536): CardTaskDto
    {
        return $this->client->post(new CardTaskCreateAction(
            taskListId: $taskListId,
            name: $name,
            position: $position,
        ));
    }

    /** 'PATCH /api/tasks/:id' */
    public function update(CardTaskDto $task): CardTaskDto
    {
        return $this->client->patch(new CardTaskUpdateAction(
            taskId: $task->id,
            data: [
                'name' => $task->name,
                'position' => $task->position,
                'isCompleted' => $task->isCompleted,
            ],
        ));
    }

    /**
     * 'PATCH /api/tasks/:id' - Partially updates task properties.
     *
     * @param string $taskId Task ID
     * @param array{
     *   name?: string,
     *   position?: int,
     *   isCompleted?: bool,
     *   linkedCardId?: string|null,
     *   assigneeUserId?: string|null
     * } $map Associative array of fields to update
     */
    public function patching(string $taskId, array $map): CardTaskDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/tasks/{$taskId}",
            data: $map,
            hydrateCallback: new CardTaskDtoFactory(),
        ));
    }

    /** 'DELETE /api/tasks/:id' */
    public function delete(string $taskId): CardTaskDto
    {
        return $this->client->delete(new CardTaskDeleteAction(taskId: $taskId));
    }
}
