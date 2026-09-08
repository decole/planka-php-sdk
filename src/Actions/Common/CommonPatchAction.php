<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;

final class CommonPatchAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    /**
     * @param OutputInterface|callable $hydrateCallback
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

    public function getFactory(): OutputInterface|callable
    {
        return $this->hydrateCallback;
    }
}
