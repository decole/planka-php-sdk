<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\TaskListHydrateTrait;

final class TaskListUpdateAction implements ActionInterface, ResponseResultInterface
{
    use TaskListHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $id,
        ?string $name = null,
        ?int $position = null,
        ?bool $showOnFrontOfCard = null,
        ?bool $hideCompletedTasks = null,
    ) {
        $body = [];

        if (null !== $name) {
            $body['name'] = $name;
        }

        if (null !== $position) {
            $body['position'] = $position;
        }

        if (null !== $showOnFrontOfCard) {
            $body['showOnFrontOfCard'] = $showOnFrontOfCard;
        }

        if (null !== $hideCompletedTasks) {
            $body['hideCompletedTasks'] = $hideCompletedTasks;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/task-lists/{$this->id}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
