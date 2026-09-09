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
     * @see Payload structure:
     *      array{
     *          id: string,
     *          name: string,
     *          description?: ?string,
     *          ownerProjectManagerId?: ?string,
     *          backgroundImageId?: ?string,
     *          backgroundType?: ?string,
     *          backgroundGradient?: ?string,
     *          isHidden?: ?bool,
     *          createdAt?: ?string,
     *          updatedAt?: ?string,
     *          background?: ?array,
     *          backgroundImage?: ?array
     *      }
     */
    public function create(array $data): ProjectDto
    {
        $item = $data['item'] ?? $data;

        if (!isset($item['id']) || !is_string($item['id'])) {
            throw new PlankaHydrationException('Failed to hydrate ProjectDto: missing or invalid "id" field.');
        }
        $bgType = isset($item['backgroundType']) && is_string($item['backgroundType']) ? BackgroundTypeEnum::tryFrom($item['backgroundType']) : null;
        $bgGrad = isset($item['backgroundGradient']) && is_string($item['backgroundGradient']) ? BackgroundGradientEnum::tryFrom($item['backgroundGradient']) : null;

        $backgroundData = $item['background'] ?? $item;

        return new ProjectDto(
            id: $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            name: $item['name'] ?? '',
            background: (new BackgroundDtoFactory())->create($backgroundData),
            backgroundImage: (new BackgroundImageDtoFactory())->create($item['backgroundImage'] ?? null),
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
