<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Background;

use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Views\Dto\Background\BackgroundDto;

final class BackgroundDtoFactory
{
    /**
     * @see Payload structure:
     *     type?: ?string,
     *     backgroundType?: ?string,
     *     gradient?: ?string,
     *     backgroundGradient?: ?string,
     *     name?: ?string
     * }
     */
    public function create(?array $data): ?BackgroundDto
    {
        if (null === $data || [] === $data) {
            return null;
        }

        $typeRaw = $data['type'] ?? $data['backgroundType'] ?? null;
        $gradientRaw = $data['gradient'] ?? $data['backgroundGradient'] ?? $data['name'] ?? null;

        $type = is_string($typeRaw) ? BackgroundTypeEnum::tryFrom($typeRaw) : null;
        $gradient = is_string($gradientRaw) ? BackgroundGradientEnum::tryFrom($gradientRaw) : null;

        return new BackgroundDto(
            type: $type,
            gradient: $gradient,
            name: $gradient,
            _rawResponse: $data,
        );
    }
}
