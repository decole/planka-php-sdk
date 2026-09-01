<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\CardTaskHydrateTrait;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;

final class CardTaskUpdateAction implements ActionInterface, ResponseResultInterface
{
    use CardTaskHydrateTrait;

    public function __construct(private readonly CardTaskDto $task) {}

    public function url(): string
    {
        return "api/tasks/{$this->task->id}";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->task->name,
                'isCompleted' => $this->task->isCompleted,
                'position' => $this->task->position,
            ],
        ];
    }
}
