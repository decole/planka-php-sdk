<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\User;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\User\UserDto;

final class UserDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     email: string,
     *     isAdmin: bool,
     *     name: string,
     *     username: string,
     *     phone: ?string,
     *     organization: ?string,
     *     language: string,
     *     subscribeToOwnCards: bool,
     *     deletedAt: ?string,
     *     avatarUrl: ?string,
     * } $data
     */
    public function create(array $data): UserDto
    {
        return new UserDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            deletedAt: $this->convertToDateTime($data['deletedAt']),
            email: $data['email'],
            isAdmin: (bool) $data['isAdmin'],
            name: $data['name'],
            username: $data['username'],
            phone: $data['phone'],
            organization: $data['organization'],
            language: $data['language'],
            subscribeToOwnCards: (bool) $data['subscribeToOwnCards'],
            avatarUrl: $data['avatarUrl'] ?? null,
        );
    }
}
