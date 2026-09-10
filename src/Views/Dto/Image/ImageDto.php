<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Image;

final class ImageDto
{
    public function __construct(
        public readonly int $height,
        public readonly int $width,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
