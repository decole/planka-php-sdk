<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\CustomField;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;

final class CustomFieldGroupDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     boardId?: ?string,
     *     cardId?: ?string,
     *     baseCustomFieldGroupId?: ?string,
     *     position: int,
     *     name?: ?string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * } $data
     */
    public function create(array $data): CustomFieldGroupDto
    {
        return new CustomFieldGroupDto(
            id: $data['id'],
            boardId: $data['boardId'] ?? null,
            cardId: $data['cardId'] ?? null,
            baseCustomFieldGroupId: $data['baseCustomFieldGroupId'] ?? null,
            position: (int) $data['position'],
            name: $data['name'] ?? null,
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
