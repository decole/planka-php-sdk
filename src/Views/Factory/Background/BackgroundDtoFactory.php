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

        $type = null;

        if (is_string($typeRaw)) {
            $type = BackgroundTypeEnum::tryFrom($typeRaw);
        }

        $gradient = null;

        if (is_string($gradientRaw)) {
            $gradient = BackgroundGradientEnum::tryFrom($gradientRaw);
        }

        return new BackgroundDto(
            type: $type,
            gradient: $gradient,
            name: $gradient,
            _rawResponse: $data,
        );
    }
}
