<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Enum\LanguageEnum;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GetTermsAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(private readonly ?LanguageEnum $language = null) {}

    public function url(): string
    {
        return 'api/terms';
    }

    public function getOptions(): array
    {
        if (null !== $this->language) {
            return [
                'query' => [
                    'language' => $this->language->value,
                ],
            ];
        }

        return [];
    }

    public function hydrate(ResponseInterface $response): array
    {
        return $response->toArray();
    }
}
