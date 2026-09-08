<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

interface PatchInputInterface
{
    /** @return array<string, mixed> */
    public function toArray(): array;
}
