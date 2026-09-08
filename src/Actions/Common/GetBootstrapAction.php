<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;

final class GetBootstrapAction implements ActionInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/bootstrap';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): callable
    {
        return static fn(array $data): array => $data;
    }
}
