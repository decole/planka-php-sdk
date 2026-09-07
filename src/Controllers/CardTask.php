<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardTask\CardTaskCreateAction;
use Planka\Bridge\Actions\CardTask\CardTaskDeleteAction;
use Planka\Bridge\Actions\CardTask\CardTaskUpdateAction;
use Planka\Bridge\Actions\CardTask\TaskListCreateAction;
use Planka\Bridge\Actions\CardTask\TaskListDeleteAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Config;
use Planka\Bridge\Traits\CardTaskHydrateTrait;
use Planka\Bridge\Traits\TaskListHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Views\Dto\Card\TaskListDto;

final class CardTask
{
    use CardTaskHydrateTrait;
    use TaskListHydrateTrait {
        TaskListHydrateTrait::hydrate insteadof CardTaskHydrateTrait;
        TaskListHydrateTrait::hydrate as hydrateTaskList;
        CardTaskHydrateTrait::hydrate as hydrateCardTask;
    }

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

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
        return $this->client->get(new \Planka\Bridge\Actions\CardTask\TaskListViewAction(id: $taskListId));
    }

    /** 'PATCH /api/task-lists/:id' */
    public function updateTaskList(
        string $taskListId,
        ?string $name = null,
        ?int $position = null,
        ?bool $showOnFrontOfCard = null,
        ?bool $hideCompletedTasks = null,
    ): TaskListDto {
        return $this->client->patch(new \Planka\Bridge\Actions\CardTask\TaskListUpdateAction(
            id: $taskListId,
            name: $name,
            position: $position,
            showOnFrontOfCard: $showOnFrontOfCard,
            hideCompletedTasks: $hideCompletedTasks,
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
            hydrateCallback: fn($response) => $this->hydrateTaskList($response),
        ));
    }

    /** 'DELETE /api/task-lists/:id' */
    public function deleteTaskList(string $taskListId): TaskListDto
    {
        return $this->client->delete(new TaskListDeleteAction(id: $taskListId));
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
        return $this->client->patch(new CardTaskUpdateAction(task: $task));
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
            hydrateCallback: fn($response) => $this->hydrateCardTask($response),
        ));
    }

    /** 'DELETE /api/tasks/:id' */
    public function delete(string $taskId): CardTaskDto
    {
        return $this->client->delete(new CardTaskDeleteAction(taskId: $taskId));
    }
}
