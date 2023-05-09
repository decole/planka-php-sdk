<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\User\UserCreateAction;
use Planka\Bridge\Actions\User\UserListAction;
use Planka\Bridge\Actions\User\UserViewAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\User\UserDto;

final class User
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /**
     * 'GET /api/users'
     * @return UserDto
     */
    public function list(): array
    {
        return $this->client->get(new UserListAction($this->config->getAuthToken()));
    }

    /** 'POST /api/users' */
    public function create(string $email, string $name, string $password, string $username): UserDto
    {
        return $this->client->post(new UserCreateAction(
            token: $this->config->getAuthToken(),
            email: $email,
            name: $name,
            password: $password,
            username: $username
        ));
    }

    /** 'GET /api/users/:id' */
    public function get(string $id): UserDto
    {
        return $this->client->get(new UserViewAction(token: $this->config->getAuthToken(), id: $id));
    }

    /** 'PATCH /api/users/:id': 'users/update', */
    public function update()
    {

    }

    /** 'PATCH /api/users/:id/email': 'users/update-email', */
    public function updateEmail()
    {

    }

    /** 'PATCH /api/users/:id/password': 'users/update-password', */
    public function updatePassword()
    {

    }

    /** 'PATCH /api/users/:id/username': 'users/update-username', */
    public function updateUsername()
    {

    }

    /** 'POST /api/users/:id/avatar': 'users/update-avatar', */
    public function updateAvatar()
    {

    }

    /** 'DELETE /api/users/:id': 'users/delete', */
    public function delete()
    {

    }
}