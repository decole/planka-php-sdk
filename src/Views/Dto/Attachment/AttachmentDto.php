<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Attachment;

use Planka\Bridge\Views\Dto\Image\ImageDto;

class AttachmentDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $cardId,
        public readonly ?string $url = null,
        public readonly ?string $creatorUserId = null,
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
        public readonly ?string $coverUrl = null,
        public readonly ?ImageDto $image = null,
        public readonly ?string $type = null,
        public readonly array $data = [],
        public readonly array $_rawResponse = [],
    ) {}
}
