<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Image;

use Planka\Bridge\Views\Dto\Image\ImageDto;

final class ImageDtoFactory
{
    /**
     * @param array<string, mixed>|null $data
     *
     * @see Payload structure:
     * array{
     *     height: int,
     *     width: int
     * }
     */
    public function create(?array $data): ?ImageDto
    {
        if (null === $data) {
            return null;
        }

        return new ImageDto(
            height: isset($data['height']) ? (int) $data['height'] : 0,
            width: isset($data['width']) ? (int) $data['width'] : 0,
            _rawResponse: $data,
        );
    }
}
