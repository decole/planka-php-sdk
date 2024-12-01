<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Image;

class ImageDto
{
    public function __construct(
        public readonly int $height,
        public readonly int $width,
    ) {}
}
