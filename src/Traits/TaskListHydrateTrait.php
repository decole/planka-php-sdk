<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\Card\TaskListDto;
use Planka\Bridge\Views\Factory\Card\TaskListDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait TaskListHydrateTrait
{
    final public function hydrate(ResponseInterface $response): TaskListDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new TaskListDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
