<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;
use Planka\Bridge\Views\Factory\AccessToken\AccessTokenDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait AccessTokenHydrateTrait
{
    final public function hydrate(ResponseInterface $response): AccessTokenDto
    {
        return (new AccessTokenDtoFactory())->create($response->toArray());
    }
}
