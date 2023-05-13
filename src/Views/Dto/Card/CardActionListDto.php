<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

class CardActionListDto
{
    public function __construct(
        public readonly array $items,
        public readonly array $included
    ) {
    }
}