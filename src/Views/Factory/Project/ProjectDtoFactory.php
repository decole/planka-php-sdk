<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Exceptions\PlankaHydrationException;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Factory\Background\BackgroundDtoFactory;
use Planka\Bridge\Views\Factory\Background\BackgroundImageDtoFactory;

final class ProjectDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @throws PlankaHydrationException
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     name: string,
     *     description?: ?string,
     *     ownerProjectManagerId?: ?string,
     *     backgroundImageId?: ?string,
     *     backgroundType?: ?string,
     *     backgroundGradient?: ?string,
     *     isHidden?: ?bool,
     *     createdAt?: ?string,
     *     updatedAt?: ?string,
     *     background?: ?array,
     *     backgroundImage?: ?array
     * }
     */
    public function create(array $data): ProjectDto
    {
        $item = $data['item'] ?? $data;

        if (!isset($item['id']) || !is_string($item['id'])) {
            throw new PlankaHydrationException('Failed to hydrate ProjectDto: missing or invalid "id" field.');
        }

        $bgType = null;

        if (isset($item['backgroundType']) && is_string($item['backgroundType'])) {
            $bgType = BackgroundTypeEnum::tryFrom($item['backgroundType']);
        }

        $bgGrad = null;

        if (isset($item['backgroundGradient']) && is_string($item['backgroundGradient'])) {
            $bgGrad = BackgroundGradientEnum::tryFrom($item['backgroundGradient']);
        }

        $backgroundData = null;

        if (isset($item['background']) && is_array($item['background'])) {
            $backgroundData = $item['background'];
        } elseif (is_array($item)) {
            $backgroundData = $item;
        }

        $backgroundImage = null;

        if (isset($item['backgroundImage']) && is_array($item['backgroundImage'])) {
            $backgroundImage = (new BackgroundImageDtoFactory())->create($item['backgroundImage']);
        }

        return new ProjectDto(
            id: $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            name: $item['name'] ?? '',
            background: is_array($backgroundData) ? (new BackgroundDtoFactory())->create($backgroundData) : null,
            backgroundImage: $backgroundImage,
            ownerProjectManagerId: $item['ownerProjectManagerId'] ?? null,
            backgroundImageId: $item['backgroundImageId'] ?? null,
            description: $item['description'] ?? null,
            isHidden: (bool) ($item['isHidden'] ?? false),
            backgroundType: $bgType,
            backgroundGradient: $bgGrad,
            _rawResponse: $data,
        );
    }
}
