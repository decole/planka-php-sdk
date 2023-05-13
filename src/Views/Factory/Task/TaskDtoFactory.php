<?php

namespace Planka\Bridge\Views\Factory\Task;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Label\LabelDto;

class TaskDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     color: ?string,
     *     boardId: string
     * } $data
     * @return LabelDto todo fix it
     */
    public function create(array $data): mixed
    {
        // TODO: Implement create() method.
    }
}