<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;
use Planka\Bridge\Views\Factory\SystemConfig\SystemConfigDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait SystemConfigHydrateTrait
{
    final public function hydrate(ResponseInterface $response): SystemConfigDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new SystemConfigDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
