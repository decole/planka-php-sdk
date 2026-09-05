<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Factory\Background\BackgroundDtoFactory;
use Planka\Bridge\Views\Factory\Background\BackgroundImageDtoFactory;

final class ProjectDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(array $data): ProjectDto
    {
        $bgType = isset($data['backgroundType']) && is_string($data['backgroundType']) ? BackgroundTypeEnum::tryFrom($data['backgroundType']) : null;
        $bgGrad = isset($data['backgroundGradient']) && is_string($data['backgroundGradient']) ? BackgroundGradientEnum::tryFrom($data['backgroundGradient']) : null;

        return new ProjectDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            name: $data['name'] ?? '',
            background: (new BackgroundDtoFactory())->create($data['background'] ?? null),
            backgroundImage: (new BackgroundImageDtoFactory())->create($data['backgroundImage'] ?? null),
            ownerProjectManagerId: $data['ownerProjectManagerId'] ?? null,
            backgroundImageId: $data['backgroundImageId'] ?? null,
            description: $data['description'] ?? null,
            isHidden: (bool) ($data['isHidden'] ?? false),
            backgroundType: $bgType,
            backgroundGradient: $bgGrad,
            _rawResponse: $data,
        );
    }
}
