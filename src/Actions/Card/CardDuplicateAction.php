<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;

final class CardDuplicateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $cardId,
        ?string $listId = null,
        int $position = 65536,
    ) {
        $body = ['position' => $position];

        if (null !== $listId) {
            $body['listId'] = $listId;
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

    public function getFactory(): OutputInterface
    {
        return new CardDtoFactory();
    }
}
