<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;
use Planka\Bridge\Views\Dto\User\UserDto;

class CardActionIncludedDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    /**
     * @param list<UserDto> $users
     */
    public function __construct(
        public readonly array $users = [],
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
