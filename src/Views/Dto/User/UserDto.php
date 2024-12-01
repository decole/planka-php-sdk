<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class UserDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly ?\DateTimeImmutable $deletedAt,
        public ?string $email,
        public bool $isAdmin,
        public ?string $name,
        public ?string $username,
        public ?string $phone,
        public ?string $organization,
        public ?string $language,
        public bool $subscribeToOwnCards,
        public ?string $avatarUrl,
    ) {}
}
