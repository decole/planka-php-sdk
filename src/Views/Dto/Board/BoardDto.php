<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class BoardDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?BoardItemDto $item,
        public readonly ?BoardIncludedDto $included,
    ) {}
}
