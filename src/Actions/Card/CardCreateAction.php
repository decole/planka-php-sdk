<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;

final class CardCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $listId,
        string $name,
        int $position = 65536,
        BoardDefaultCardTypeEnum $type = BoardDefaultCardTypeEnum::PROJECT,
    ) {
        $this->options['json'] = [
            'type' => $type->value,
            'name' => $name,
            'position' => $position,
        ];
    }

    public function url(): string
    {
        return "api/lists/{$this->listId}/cards";
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
