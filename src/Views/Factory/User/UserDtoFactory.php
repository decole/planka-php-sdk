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

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     *      array{
     *          id: string,
     *          email?: ?string,
     *          role?: ?string,
     *          name?: ?string,
     *          username?: ?string,
     *          avatar?: ?array{url: string, thumbnailUrls: array},
     *          avatarUrl?: ?string,
     *          phone?: ?string,
     *          organization?: ?string,
     *          language?: ?string,
     *          subscribeToOwnCards?: ?bool,
     *          subscribeToCardWhenCommenting?: ?bool,
     *          turnOffRecentCardHighlighting?: ?bool,
     *          enableFavoritesByDefault?: ?bool,
     *          defaultEditorMode?: ?string,
     *          defaultHomeView?: ?string,
     *          defaultProjectsOrder?: ?string,
     *          isSsoUser?: ?bool,
     *          isDeactivated?: ?bool,
     *          isDefaultAdmin?: ?bool,
     *          lockedFieldNames?: array,
     *          createdAt?: ?string,
     *          updatedAt?: ?string
     *      }
     */
    public function create(array $data): UserDto
    {
        $item = $data['item'] ?? $data;

        $isAdmin = isset($item['isAdmin'])
            ? (bool) $item['isAdmin']
            : ('admin' === ($item['role'] ?? null));

        $avatarUrl = $item['avatarUrl'] ?? null;

        if (null === $avatarUrl && is_array($item['avatar'] ?? null)) {
            $avatarUrl = $item['avatar']['url'] ?? null;
        }

        $roleEnum = null;

        if (isset($item['role']) && is_string($item['role'])) {
            $roleEnum = UserRoleEnum::tryFrom($item['role']);
        }

        return new UserDto(
            id: $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            email: $item['email'] ?? null,
            isAdmin: $isAdmin,
            name: $item['name'] ?? null,
            username: $item['username'] ?? null,
            phone: $item['phone'] ?? null,
            organization: $item['organization'] ?? null,
            language: $item['language'] ?? null,
            subscribeToOwnCards: (bool) ($item['subscribeToOwnCards'] ?? false),
            deletedAt: $this->convertToDateTime($item['deletedAt'] ?? null),
            isLocked: (bool) ($item['isLocked'] ?? false),
            isRoleLocked: (bool) ($item['isRoleLocked'] ?? false),
            isUsernameLocked: (bool) ($item['isUsernameLocked'] ?? false),
            isDeletionLocked: (bool) ($item['isDeletionLocked'] ?? false),
            avatarUrl: $avatarUrl,
            avatar: is_array($item['avatar'] ?? null) ? $item['avatar'] : null,
            role: $roleEnum,
            isDeactivated: (bool) ($item['isDeactivated'] ?? false),
            isSsoUser: (bool) ($item['isSsoUser'] ?? false),
            lockedFieldNames: (array) ($item['lockedFieldNames'] ?? []),
            _rawResponse: $data,
        );
    }
}
