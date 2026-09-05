<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Views\Dto\Terms\TermsDto;
use Planka\Bridge\Views\Factory\Terms\TermsDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait TermsHydrateTrait
{
    final public function hydrate(ResponseInterface $response): TermsDto
    {
        return (new TermsDtoFactory())->create($response->toArray());
    }
}
