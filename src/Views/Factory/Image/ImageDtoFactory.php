<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Image;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Image\ImageDto;

final class ImageDtoFactory implements OutputInterface
{
    public function create(?array $data): ?ImageDto
    {
        if (empty($data)) {
            return null;
        }

        return new ImageDto(
            height: (int)$data['height'],
            width: (int)$data['width']
        );
    }
}