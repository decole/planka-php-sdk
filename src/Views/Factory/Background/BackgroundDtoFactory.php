<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Background;

use Planka\Bridge\Views\Dto\Background\BackgroundDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;

final class BackgroundDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     type: string,
     *     name: ?string
     * }|null $data
     * @return ?BackgroundDto
     */
    public function create(?array $data): ?BackgroundDto
    {
        if (empty($data)) {
            return null;
        }

        return new BackgroundDto(
            type: BackgroundTypeEnum::from($data['type']),
            name: BackgroundGradientEnum::tryFrom($data['name'] ?? '')
        );
    }
}