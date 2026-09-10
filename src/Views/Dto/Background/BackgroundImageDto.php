<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Background;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class BackgroundImageDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $projectId = null,
        public readonly ?string $size = null,
        public readonly ?string $url = null,
        public readonly ?string $coverUrl = null,
        public readonly array $thumbnailUrls = [],
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
