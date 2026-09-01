<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\TaskListHydrateTrait;

final class TaskListCreateAction implements ActionInterface, ResponseResultInterface
{
    use TaskListHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $cardId,
        string $name,
        int $position = 65536,
        ?bool $showOnFrontOfCard = null,
        ?bool $hideCompletedTasks = null,
    ) {
        $body = [
            'name' => $name,
            'position' => $position,
        ];

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
        return "api/cards/{$this->cardId}/task-lists";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
