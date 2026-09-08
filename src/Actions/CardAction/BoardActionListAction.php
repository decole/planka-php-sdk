<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardAction;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\CardActionListDtoFactory;

final class BoardActionListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $boardId,
        private readonly ?string $beforeId = null,
    ) {}

    public function url(): string
    {
        return "api/boards/{$this->boardId}/actions";
    }

    public function getOptions(): array
    {
        if (null !== $this->beforeId) {
            return [
                'query' => [
                    'beforeId' => $this->beforeId,
                ],
            ];
        }

        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new CardActionListDtoFactory();
    }
}
