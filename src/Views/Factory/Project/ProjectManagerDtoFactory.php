<?php

declare(strict_types = 1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;

final class ProjectManagerDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     projectId: string,
     *     userId: string
     * } $data
     * @return ProjectManagerDto
     */
    public function create(array $data): ProjectManagerDto
    {
        return new ProjectManagerDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            projectId: $data['projectId'],
            userId: $data['userId']
        );
    }
}