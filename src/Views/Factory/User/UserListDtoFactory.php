<?php

namespace Planka\Bridge\Views\Factory\User;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\User\UserDto;
use function Fp\Collection\map;

final class UserListDtoFactory implements OutputInterface
{
    /**
     * @return UserDto
     */
    public function create(array $data): array
    {
        return map($data['items'] ?? [], fn(array $item) => (new UserDtoFactory())->create($item));
    }
}