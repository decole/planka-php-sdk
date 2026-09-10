<?php

declare(strict_types=1);

namespace Planka\Bridge\Builders;

use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Inputs\UserPatchInput;

final class UserBuilder
{
    private ?string $name = null;

    private ?string $username = null;

    private ?string $email = null;

    private string|UserRoleEnum|null $role = null;

    private ?bool $isDeactivated = null;

    public function __construct(?string $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setRole(string|UserRoleEnum|null $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setIsDeactivated(?bool $isDeactivated): self
    {
        $this->isDeactivated = $isDeactivated;

        return $this;
    }

    public function build(): UserPatchInput
    {
        return new UserPatchInput(
            name: $this->name,
            username: $this->username,
            email: $this->email,
            role: $this->role,
            isDeactivated: $this->isDeactivated,
        );
    }
}
