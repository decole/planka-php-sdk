<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\CardHydrateTrait;

final class CardDuplicateAction implements ActionInterface, ResponseResultInterface
{
    use CardHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $cardId,
        ?string $boardId = null,
        ?string $listId = null,
        int $position = 65536,
        ?string $name = null,
    ) {
        $body = [
            'position' => $position,
        ];

        if (null !== $boardId) {
            $body['boardId'] = $boardId;
        }

        if (null !== $listId) {
            $body['listId'] = $listId;
        }

        if (null !== $name) {
            $body['name'] = $name;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/duplicate";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
