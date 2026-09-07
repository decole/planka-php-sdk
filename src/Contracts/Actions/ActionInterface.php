<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Actions;

interface ActionInterface
{
    public function url(): string;

    /** @return array<string, mixed> */
    public function getOptions(): array;
}
