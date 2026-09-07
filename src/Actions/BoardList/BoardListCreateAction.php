<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardList;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Views\Factory\Board\BoardListDtoFactory;

final class BoardListCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $boardId,
        string $name,
        int $position,
        ListTypeEnum $type = ListTypeEnum::ACTIVE,
    ) {
        $this->options['json'] = [
            'type' => $type->value,
            'name' => $name,
            'position' => $position,
        ];
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}/lists";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new BoardListDtoFactory();
    }
}
