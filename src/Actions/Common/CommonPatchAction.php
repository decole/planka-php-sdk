<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CommonPatchAction implements ActionInterface, ResponseResultInterface
{
    /**
     * @param callable(ResponseInterface): mixed $hydrateCallback
     */
    public function __construct(
        private readonly string $urlPath,
        private readonly array $data,
        private readonly mixed $hydrateCallback,
    ) {}

    public function url(): string
    {
        return $this->urlPath;
    }

    public function getOptions(): array
    {
        return [
            'json' => $this->data,
        ];
    }

    public function hydrate(ResponseInterface $response): mixed
    {
        return ($this->hydrateCallback)($response);
    }
}
