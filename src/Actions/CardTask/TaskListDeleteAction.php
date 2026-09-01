<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\TaskListHydrateTrait;

final class TaskListDeleteAction implements ActionInterface, ResponseResultInterface
{
    use TaskListHydrateTrait;

    public function __construct(private readonly string $id) {}

    public function url(): string
    {
        return "api/task-lists/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
