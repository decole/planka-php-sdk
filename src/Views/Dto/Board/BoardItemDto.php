<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use DateTimeImmutable;

final class BoardItemDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $projectId,
        public readonly ?int $position,
        public readonly ?string $name,
        public readonly ?DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt = null,
    ) {
    }
}