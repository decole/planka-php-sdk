<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Attachment;

use DateTimeImmutable;
use Planka\Bridge\Views\Dto\Image\ImageDto;

class AttachmentDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $cardId,
        public readonly string $url,
        public readonly string $creatorUserId,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?string $coverUrl = null,
        public readonly ?ImageDto $image = null
    ) {
    }
}