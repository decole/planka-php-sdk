<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Views\Factory\Background\BackgroundImageDtoFactory;
use Planka\Bridge\Views\Factory\Background\BackgroundDtoFactory;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class ProjectDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     name: string,
     *     background: array{type: string,name?: ?string}|null,
     *     backgroundImage: array{url: string, coverUrl: string}|null
     * } $data
     */
    public function create(array $data): ProjectDto
    {
        return new ProjectDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            name: $data['name'],
            background: (new BackgroundDtoFactory())->create($data['background'] ?? null),
            backgroundImage: (new BackgroundImageDtoFactory())->create($data['backgroundImage'] ?? null),
            _rawResponse: $data,
        );
    }
}
