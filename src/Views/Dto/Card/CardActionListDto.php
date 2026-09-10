<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class CardActionListDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    /**
     * @param list<CardActionItemDto> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly CardActionIncludedDto $included,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
