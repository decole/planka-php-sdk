<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Comment;

use Planka\Bridge\Enum\CommentTypeEnum;
use DateTimeImmutable;

class CommentDto
{
    public function __construct(
        public readonly string $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt,
        public readonly string $cardId,
        public readonly string $userId,
        public readonly CommentTypeEnum $type,
        public string $dataText
    ) {
    }
}