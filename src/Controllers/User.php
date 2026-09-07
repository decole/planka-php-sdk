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
use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\User\ApiKeyDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class User
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /**
     * 'GET /api/users'.
     *
     * @return UserDto[]
     */
    public function list(): array
    {
        return $this->client->get(new UserListAction());
    }

    /** 'POST /api/users' */
    public function create(string $email, string $name, string $password, string $username): UserDto
    {
        return $this->client->post(new UserCreateAction(
            email: $email,
            name: $name,
            password: $password,
            username: $username,
        ));
    }

    /** 'GET /api/users/:id' */
    public function get(string $id): UserDto
    {
        return $this->client->get(new UserViewAction(userId: $id));
    }

    /** 'PATCH /api/users/:id' */
    public function update(UserDto $dto): UserDto
    {
        return $this->client->patch(new UserUpdateAction(userId: $dto->id, data: $dto->toArray()));
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
            hydrateCallback: new UserDtoFactory(),
        ));
    }

    /** 'PATCH /api/users/:id/email' */
    public function updateEmail(UserDto $dto): UserDto
    {
        return $this->client->patch(new UserUpdateEmailAction(userId: $dto->id, email: $dto->email));
    }

    /** 'PATCH /api/users/:id/password' */
    public function updatePassword(string $id, string $current, string $new): UserDto
    {
        return $this->client->patch(new UserUpdatePasswordAction(
            userId: $id,
            password: $new,
            currentPassword: $current,
        ));
    }

    /** 'PATCH /api/users/:id/username' */
    public function updateUsername(UserDto $dto): UserDto
    {
        return $this->client->patch(new UserUpdateUsernameAction(userId: $dto->id, username: $dto->username));
    }

    /**
     * 'POST /api/users/:id/avatar'.
     *
     * @throws FileExistException
     */
    public function updateAvatar(UserDto $dto, string $file): UserDto
    {
        return $this->client->post(new UserUpdateAvatarAction(
            userId: $dto->id,
            file: $file,
        ));
    }

    /** 'DELETE /api/users/:id' */
    public function delete(UserDto $dto): UserDto
    {
        return $this->client->delete(new UserDeleteAction(userId: $dto->id));
    }

    /** 'POST /api/users/:id/api-key' */
    public function createApiKey(string $userId): ApiKeyDto
    {
        return $this->client->post(new UserCreateApiKeyAction($userId));
    }
}
