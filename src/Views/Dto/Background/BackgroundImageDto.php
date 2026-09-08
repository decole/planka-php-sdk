<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Background;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class BackgroundImageDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $projectId = null,
        public readonly ?string $size = null,
        public readonly ?string $url = null,
        public readonly ?string $coverUrl = null,
        public readonly array $thumbnailUrls = [],
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
        public readonly array $_rawResponse = [],
    ) {}
}
