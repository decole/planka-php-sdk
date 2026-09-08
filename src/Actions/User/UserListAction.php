<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\ItemDtoListFactory;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/users';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new ItemDtoListFactory(new UserDtoFactory());
    }
}
