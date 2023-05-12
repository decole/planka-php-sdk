<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Views\Factory\Board\BoardListDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Views\Dto\Board\BoardListDto;
use Planka\Bridge\Exceptions\ResponseException;

trait BoardListHydrateTrait
{
    final public function hydrate(ResponseInterface $response): BoardListDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new BoardListDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}