<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Notification;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class NotificationIncludedDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly array $users,
        public readonly array $cards,
        public readonly array $actions,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
