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
     * array{
     *     id: string,
     *     email?: ?string,
     *     role?: ?string,
     *     name?: ?string,
     *     username?: ?string,
     *     avatar?: ?array{url: string, thumbnailUrls: array},
     *     avatarUrl?: ?string,
     *     phone?: ?string,
     *     organization?: ?string,
     *     language?: ?string,
     *     subscribeToOwnCards?: ?bool,
     *     subscribeToCardWhenCommenting?: ?bool,
     *     turnOffRecentCardHighlighting?: ?bool,
     *     enableFavoritesByDefault?: ?bool,
     *     defaultEditorMode?: ?string,
     *     defaultHomeView?: ?string,
     *     defaultProjectsOrder?: ?string,
     *     isSsoUser?: ?bool,
     *     isDeactivated?: ?bool,
     *     isDefaultAdmin?: ?bool,
     *     lockedFieldNames?: array,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
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
            id: (string) $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            email: isset($item['email']) && is_string($item['email']) ? $item['email'] : null,
            isAdmin: $isAdmin,
            name: isset($item['name']) && is_string($item['name']) ? $item['name'] : null,
            username: isset($item['username']) && is_string($item['username']) ? $item['username'] : null,
            phone: isset($item['phone']) && is_string($item['phone']) ? $item['phone'] : null,
            organization: isset($item['organization']) && is_string($item['organization']) ? $item['organization'] : null,
            language: isset($item['language']) && is_string($item['language']) ? $item['language'] : null,
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
            isDefaultAdmin: (bool) ($item['isDefaultAdmin'] ?? false),
            apiKeyPrefix: isset($item['apiKeyPrefix']) && is_string($item['apiKeyPrefix']) ? $item['apiKeyPrefix'] : null,
            isTotpEnabled: (bool) ($item['isTotpEnabled'] ?? false),
            totpEnabledAt: $this->convertToDateTime($item['totpEnabledAt'] ?? null),
            totpRecoveryCodesRemaining: isset($item['totpRecoveryCodesRemaining']) ? (int) $item['totpRecoveryCodesRemaining'] : null,
            lockedFieldNames: (array) ($item['lockedFieldNames'] ?? []),
            _rawResponse: $data,
        );
    }
}
