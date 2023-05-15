<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\CardTaskHydrateTrait;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardTaskUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardTaskHydrateTrait;

    public function __construct(private readonly CardTaskDto $task, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/tasks/{$this->task->id}";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'name' => $this->task->name,
                'position' => $this->task->position,
                'isCompleted' => $this->task->isCompleted,
            ],
        ];
    }
}