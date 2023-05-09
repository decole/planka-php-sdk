<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

use DateTimeImmutable;
use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class UserDto implements OutputDtoInterface
{
    public function __construct(
        public ?string $id,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
        public ?string $email,
        public bool $isAdmin,
        public ?string $name,
        public ?string $username,
        public ?string $phone,
        public ?string $organization,
        public ?string $language,
        public bool $subscribeToOwnCards,
        public ?DateTimeImmutable $deletedAt,
        public ?string $avatarUrl
    ) {
    }
}