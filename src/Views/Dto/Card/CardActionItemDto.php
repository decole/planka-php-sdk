<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use DateTimeImmutable;
use Planka\Bridge\Enum\CommentTypeEnum;

class CardActionItemDto
{
    public function __construct(
        public readonly string $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt,
        public readonly CommentTypeEnum $type,
        public readonly string $dataText,
        public readonly string $cardId,
        public readonly string $userId
    ) {
    }
}