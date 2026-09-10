<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\CustomField;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;

final class BaseCustomFieldGroupDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     projectId: string,
     *     name: string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): BaseCustomFieldGroupDto
    {
        $data = $data['item'] ?? $data;

        return new BaseCustomFieldGroupDto(
            id: (string) $data['id'],
            projectId: (string) $data['projectId'],
            name: (string) $data['name'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
