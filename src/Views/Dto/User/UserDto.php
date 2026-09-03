<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\UserRoleEnum;

class UserDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public ?string $email,
        public bool $isAdmin,
        public ?string $name,
        public ?string $username,
        public ?string $phone,
        public ?string $organization,
        public ?string $language,
        public bool $subscribeToOwnCards,
        public readonly ?\DateTimeImmutable $deletedAt,
        public bool $isLocked,
        public bool $isRoleLocked,
        public bool $isUsernameLocked,
        public bool $isDeletionLocked,
        public ?string $avatarUrl,
        public ?UserRoleEnum $role = null,
        public bool $isDeactivated = false,
        public bool $isSsoUser = false,
        public array $lockedFieldNames = [],
        public readonly array $_rawResponse = [],
    ) {}
}
