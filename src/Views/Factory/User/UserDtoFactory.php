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
     *     isLocked: bool,
     *     isRoleLocked: bool,
     *     isUsernameLocked: bool,
     *     isDeletionLocked: bool,
     *     avatarUrl: ?string,
     * } $data
     */
    public function create(array $data): UserDto
    {
        return new UserDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            email: $data['email'],
            isAdmin: (bool) $data['isAdmin'],
            name: $data['name'],
            username: $data['username'],
            phone: $data['phone'],
            organization: $data['organization'],
            language: $data['language'],
            subscribeToOwnCards: (bool) $data['subscribeToOwnCards'],
            deletedAt: $this->convertToDateTime($data['deletedAt']),
            isLocked: (bool) ($data['isLocked'] ?? false),
            isRoleLocked: (bool) ($data['isRoleLocked'] ?? false),
            isUsernameLocked: (bool) ($data['isUsernameLocked'] ?? false),
            isDeletionLocked: (bool) ($data['isDeletionLocked'] ?? false),
            avatarUrl: $data['avatarUrl'] ?? null,
        );
    }
}
