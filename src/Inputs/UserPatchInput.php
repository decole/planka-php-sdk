<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Enum\UserRoleEnum;

final class UserPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $username = null,
        public readonly ?string $email = null,
        public readonly string|UserRoleEnum|null $role = null,
        public readonly ?bool $isDeactivated = null,
    ) {}

    public function toArray(): array
    {
        $roleVal = $this->role instanceof UserRoleEnum ? $this->role->value : $this->role;

        return array_filter([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $roleVal,
            'isDeactivated' => $this->isDeactivated,
        ], static fn ($v) => null !== $v);
    }
}
