<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\CardTaskHydrateTrait;

final class CardTaskDeleteAction implements ActionInterface, ResponseResultInterface
{
    use CardTaskHydrateTrait;

    public function __construct(private readonly string $taskId) {}

    public function url(): string
    {
        return "api/tasks/{$this->taskId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
