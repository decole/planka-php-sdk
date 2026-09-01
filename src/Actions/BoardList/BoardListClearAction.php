<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardList;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\BoardListHydrateTrait;

final class BoardListClearAction implements ActionInterface, ResponseResultInterface
{
    use BoardListHydrateTrait;

    public function __construct(private readonly string $listId) {}

    public function url(): string
    {
        return "api/lists/{$this->listId}/clear";
    }

    public function getOptions(): array
    {
        return [];
    }
}
