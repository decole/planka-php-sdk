<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Actions;

interface ActionInterface
{
    public function url(): string;

    /** @return list<mixed> */
    public function getOptions(): array;
}
