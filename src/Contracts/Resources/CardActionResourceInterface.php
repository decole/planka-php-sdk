<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Card\CardActionListDto;

interface CardActionResourceInterface
{
    public function getActions(string $cardId): CardActionListDto;

    public function getBoardActions(string $boardId, ?string $beforeId = null): CardActionListDto;
}
