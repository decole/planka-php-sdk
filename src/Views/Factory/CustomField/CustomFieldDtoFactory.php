<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\CustomField;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;

final class CustomFieldDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     baseCustomFieldGroupId?: ?string,
     *     customFieldGroupId?: ?string,
     *     position: int,
     *     name: string,
     *     showOnFrontOfCard?: bool,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * } $data
     */
    public function create(array $data): CustomFieldDto
    {
        return new CustomFieldDto(
            id: $data['id'],
            baseCustomFieldGroupId: $data['baseCustomFieldGroupId'] ?? null,
            customFieldGroupId: $data['customFieldGroupId'] ?? null,
            position: (int) $data['position'],
            name: $data['name'],
            showOnFrontOfCard: (bool) ($data['showOnFrontOfCard'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
        );
    }
}
