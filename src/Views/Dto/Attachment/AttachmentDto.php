<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Attachment;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;
use Planka\Bridge\Views\Dto\Image\ImageDto;

class AttachmentDto implements OutputDtoInterface
{
    use OutputDtoTrait;

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
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
