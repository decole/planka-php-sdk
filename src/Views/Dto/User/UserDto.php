<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\UserRoleEnum;

final class UserDto implements OutputDtoInterface
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
        public ?array $avatar = null,
        public ?UserRoleEnum $role = null,
        public bool $isDeactivated = false,
        public bool $isSsoUser = false,
        public array $lockedFieldNames = [],
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'email' => $this->email,
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'organization' => $this->organization,
            'language' => $this->language,
            'subscribeToOwnCards' => $this->subscribeToOwnCards,
            'avatarUrl' => $this->avatarUrl,
            'role' => $this->role?->value,
        ], static fn ($v) => null !== $v);
    }
}
