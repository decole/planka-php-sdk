<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardList;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\BoardListHydrateTrait;

final class BoardListSortAction implements ActionInterface, ResponseResultInterface
{
    use BoardListHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $listId,
        string $fieldName,
        string $order = 'asc',
    ) {
        $this->options['json'] = [
            'fieldName' => $fieldName,
            'order' => $order,
        ];
    }

    public function url(): string
    {
        return "api/lists/{$this->listId}/sort";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
