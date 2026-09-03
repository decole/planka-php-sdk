<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Enum\LanguageEnum;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class AcceptTermsAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $pendingToken,
        private readonly string $signature,
        private readonly ?LanguageEnum $initialLanguage = null,
    ) {}

    public function url(): string
    {
        return 'api/access-tokens/accept-terms';
    }

    public function getOptions(): array
    {
        $json = [
            'pendingToken' => $this->pendingToken,
            'signature' => $this->signature,
        ];

        if (null !== $this->initialLanguage) {
            $json['initialLanguage'] = $this->initialLanguage->value;
        }

        return [
            'json' => $json,
        ];
    }

    public function hydrate(ResponseInterface $response): array
    {
        return $response->toArray();
    }
}
