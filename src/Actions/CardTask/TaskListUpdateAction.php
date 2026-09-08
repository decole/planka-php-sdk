<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\TaskListDtoFactory;

final class TaskListUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $taskListId,
        array $data,
    ) {
        $this->options['json'] = $data;
    }

    public function url(): string
    {
        return "api/task-lists/{$this->taskListId}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new TaskListDtoFactory();
    }
}
