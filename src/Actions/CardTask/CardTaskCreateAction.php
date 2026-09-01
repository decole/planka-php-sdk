<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\CardTaskHydrateTrait;

final class CardTaskCreateAction implements ActionInterface, ResponseResultInterface
{
    use CardTaskHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $taskListId,
        string $name,
        int $position = 65536,
        ?string $linkedCardId = null,
        ?bool $isCompleted = null,
    ) {
        $body = [
            'name' => $name,
            'position' => $position,
        ];

        if (null !== $linkedCardId) {
            $body['linkedCardId'] = $linkedCardId;
        }

        if (null !== $isCompleted) {
            $body['isCompleted'] = $isCompleted;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/task-lists/{$this->taskListId}/tasks";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
