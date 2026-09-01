<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\NotificationService;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;

class NotificationServiceDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $userId,
        public readonly ?string $boardId,
        public string $url,
        public NotificationServiceFormatEnum $format,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {}
}
