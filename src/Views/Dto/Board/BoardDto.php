<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class BoardDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly ?BoardItemDto $item = null,
        public readonly ?BoardIncludedDto $included = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
