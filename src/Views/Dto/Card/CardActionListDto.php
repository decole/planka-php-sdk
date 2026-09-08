<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class CardActionListDto implements OutputDtoInterface
{
    /**
     * @param list<CardActionItemDto> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly CardActionIncludedDto $included,
        public readonly array $_rawResponse = [],
    ) {}
}
