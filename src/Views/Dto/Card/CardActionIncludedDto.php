<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Views\Dto\User\UserDto;

class CardActionIncludedDto implements OutputDtoInterface
{
    /**
     * @param list<UserDto> $users
     */
    public function __construct(
        public readonly array $users = [],
        public readonly array $_rawResponse = [],
    ) {}
}
