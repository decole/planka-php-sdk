<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardDto;

final class BoardDtoFactory implements OutputInterface
{
    public function create(array $data): BoardDto
    {
        return new BoardDto(
            item: (new BoardItemDtoFactory())->create($data),
            included: (new BoardIncludedDtoFactory())->create($data),
        );
    }
}