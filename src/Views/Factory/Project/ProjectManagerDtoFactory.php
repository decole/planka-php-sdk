<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Project\ProjectManagerDto;

final class ProjectManagerDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     projectId: string,
     *     userId: string
     * }
     */
    public function create(array $data): ProjectManagerDto
    {
        $data = $data['item'] ?? $data;

        return new ProjectManagerDto(
            id: (string) $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            projectId: (string) $data['projectId'],
            userId: (string) $data['userId'],
            _rawResponse: $data,
        );
    }
}
