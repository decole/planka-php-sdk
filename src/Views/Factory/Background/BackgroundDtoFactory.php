<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Background;

use Planka\Bridge\Views\Dto\Background\BackgroundDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;

final class BackgroundDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     type: string
     * }|null $data
     * @return ?BackgroundDto
     */
    public function create(?array $data): ?BackgroundDto
    {
        if (empty($data)) {
            return null;
        }

        return new BackgroundDto(
            type: $data['type']
        );
    }
}