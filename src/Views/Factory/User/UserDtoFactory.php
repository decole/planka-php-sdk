<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\User;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\User\UserDto;

final class UserDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(array $data): UserDto
    {
        $isAdmin = isset($data['isAdmin'])
            ? (bool) $data['isAdmin']
            : ('admin' === ($data['role'] ?? null));

        $avatarUrl = $data['avatarUrl'] ?? null;

        if (null === $avatarUrl && is_array($data['avatar'] ?? null)) {
            $avatarUrl = $data['avatar']['url'] ?? null;
        }

        $roleEnum = null;

        if (isset($data['role']) && is_string($data['role'])) {
            $roleEnum = UserRoleEnum::tryFrom($data['role']);
        }

        return new UserDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            email: $data['email'] ?? null,
            isAdmin: $isAdmin,
            name: $data['name'] ?? null,
            username: $data['username'] ?? null,
            phone: $data['phone'] ?? null,
            organization: $data['organization'] ?? null,
            language: $data['language'] ?? null,
            subscribeToOwnCards: (bool) ($data['subscribeToOwnCards'] ?? false),
            deletedAt: $this->convertToDateTime($data['deletedAt'] ?? null),
            isLocked: (bool) ($data['isLocked'] ?? false),
            isRoleLocked: (bool) ($data['isRoleLocked'] ?? false),
            isUsernameLocked: (bool) ($data['isUsernameLocked'] ?? false),
            isDeletionLocked: (bool) ($data['isDeletionLocked'] ?? false),
            avatarUrl: $avatarUrl,
            role: $roleEnum,
            isDeactivated: (bool) ($data['isDeactivated'] ?? false),
            isSsoUser: (bool) ($data['isSsoUser'] ?? false),
            lockedFieldNames: (array) ($data['lockedFieldNames'] ?? []),
            _rawResponse: $data,
        );
    }
}
