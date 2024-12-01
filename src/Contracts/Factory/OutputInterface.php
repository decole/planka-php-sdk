<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Factory;

interface OutputInterface
{
    public function create(array $data): mixed;
}
