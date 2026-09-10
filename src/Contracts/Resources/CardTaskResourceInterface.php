<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Inputs\CardTaskPatchInput as CardTaskPatchInputDto;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\TaskListPatchInput;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Views\Dto\Card\TaskListDto;

interface CardTaskResourceInterface
{
    public function createTaskList(string $cardId, string $name, int $position = 65536): TaskListDto;

    public function getTaskList(string $taskListId): TaskListDto;

    public function updateTaskList(
        string $taskListId,
        ?string $name = null,
        ?int $position = null,
        ?bool $showOnFrontOfCard = null,
        ?bool $hideCompletedTasks = null,
    ): TaskListDto;

    /**
     * @param array{
     *   name?: string,
     *   position?: int,
     *   showOnFrontOfCard?: bool,
     *   hideCompletedTasks?: bool
     * }|TaskListPatchInput|PatchInputInterface $map
     */
    public function patchingTaskList(string $taskListId, array|PatchInputInterface $map): TaskListDto;

    public function deleteTaskList(string $taskListId): TaskListDto;

    public function create(string $taskListId, string $name, int $position = 65536): CardTaskDto;

    public function update(CardTaskDto $task): CardTaskDto;

    /**
     * @param array{
     *   name?: string,
     *   position?: int,
     *   isCompleted?: bool,
     *   linkedCardId?: string|null,
     *   assigneeUserId?: string|null
     * }|CardTaskPatchInputDto|PatchInputInterface $map
     */
    public function patching(string $taskId, array|PatchInputInterface $map): CardTaskDto;

    public function delete(string $taskId): CardTaskDto;
}
