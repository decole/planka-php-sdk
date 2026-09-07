<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\User\UserCreateAction;
use Planka\Bridge\Actions\User\UserCreateApiKeyAction;
use Planka\Bridge\Actions\User\UserDeleteAction;
use Planka\Bridge\Actions\User\UserListAction;
use Planka\Bridge\Actions\User\UserUpdateAction;
use Planka\Bridge\Actions\User\UserUpdateAvatarAction;
use Planka\Bridge\Actions\User\UserUpdateEmailAction;
use Planka\Bridge\Actions\User\UserUpdatePasswordAction;
use Planka\Bridge\Actions\User\UserUpdateUsernameAction;
use Planka\Bridge\Actions\User\UserViewAction;
use Planka\Bridge\Config;
use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Traits\UserHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\User\UserDto;

final class User
{
    use UserHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /**
     * 'GET /api/users'.
     *
     * @return UserDto[]
     */
    public function list(): array
    {
        return $this->client->get(new UserListAction($this->config->getAuthToken()));
    }

    /** 'POST /api/users' */
    public function create(string $email, string $name, string $password, string $username): UserDto
    {
        return $this->client->post(new UserCreateAction(
            email: $email,
            name: $name,
            password: $password,
            username: $username,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'GET /api/users/:id' */
    public function get(string $id): UserDto
    {
        return $this->client->get(new UserViewAction(id: $id, token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/users/:id' */
    public function update(UserDto $dto): UserDto
    {
        return $this->client->patch(new UserUpdateAction(user: $dto, token: $this->config->getAuthToken()));
    }

    /**
     * 'PATCH /api/users/:id' - Partially updates user properties.
     *
     * @param string $userId User ID
     * @param array{
     *   name?: string,
     *   username?: string|null,
     *   email?: string,
     *   role?: 'admin'|'projectOwner'|'boardUser'|UserRoleEnum,
     *   isDeactivated?: bool
     * } $map Associative array of fields to update
     */
    public function patching(string $userId, array $map): UserDto
    {
        if (isset($map['role']) && $map['role'] instanceof UserRoleEnum) {
            $map['role'] = $map['role']->value;
        }

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/users/{$userId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'PATCH /api/users/:id/email' */
    public function updateEmail(UserDto $dto): UserDto
    {
        return $this->client->patch(new UserUpdateEmailAction(user: $dto, token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/users/:id/password' */
    public function updatePassword(string $id, string $current, string $new): UserDto
    {
        return $this->client->patch(new UserUpdatePasswordAction(
            userId: $id,
            current: $current,
            new: $new,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'PATCH /api/users/:id/username' */
    public function updateUsername(UserDto $dto): UserDto
    {
        return $this->client->patch(new UserUpdateUsernameAction(user: $dto, token: $this->config->getAuthToken()));
    }

    /**
     * 'POST /api/users/:id/avatar'.
     *
     * @throws FileExistException
     */
    public function updateAvatar(UserDto $dto, string $file): UserDto
    {
        return $this->client->post(new UserUpdateAvatarAction(
            user: $dto,
            file: $file,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'DELETE /api/users/:id' */
    public function delete(UserDto $dto): UserDto
    {
        return $this->client->delete(new UserDeleteAction(user: $dto, token: $this->config->getAuthToken()));
    }

    /** 'POST /api/users/:id/api-key' */
    public function createApiKey(string $userId): array
    {
        return $this->client->post(new UserCreateApiKeyAction($userId));
    }
}
