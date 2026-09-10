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
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     baseCustomFieldGroupId?: ?string,
     *     customFieldGroupId?: ?string,
     *     position: int,
     *     name: string,
     *     showOnFrontOfCard?: bool,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): CustomFieldDto
    {
        $data = $data['item'] ?? $data;

        return new CustomFieldDto(
            id: (string) $data['id'],
            baseCustomFieldGroupId: isset($data['baseCustomFieldGroupId']) && is_string($data['baseCustomFieldGroupId']) ? $data['baseCustomFieldGroupId'] : null,
            customFieldGroupId: isset($data['customFieldGroupId']) && is_string($data['customFieldGroupId']) ? $data['customFieldGroupId'] : null,
            position: (int) ($data['position'] ?? 0),
            name: (string) $data['name'],
            showOnFrontOfCard: (bool) ($data['showOnFrontOfCard'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
