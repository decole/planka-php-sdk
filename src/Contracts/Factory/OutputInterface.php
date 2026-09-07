<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Factory;

interface OutputInterface
{
    /** @param array<string, mixed> $data */
    public function create(array $data): mixed;
}
