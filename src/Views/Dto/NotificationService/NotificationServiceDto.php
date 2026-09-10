<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\NotificationService;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\Traits\OutputDtoTrait;

final class NotificationServiceDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly string $id,
        public readonly ?string $userId,
        public readonly ?string $boardId,
        public string $url,
        public NotificationServiceFormatEnum $format,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
