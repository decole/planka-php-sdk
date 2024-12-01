<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Background;

use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;

final class BackgroundImageDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     url: string,
     *     coverUrl: string
     * }|null $data
     */
    public function create(?array $data): ?BackgroundImageDto
    {
        if (empty($data)) {
            return null;
        }

        return new BackgroundImageDto(
            url: $data['url'],
            coverUrl: $data['coverUrl'],
        );
    }
}
