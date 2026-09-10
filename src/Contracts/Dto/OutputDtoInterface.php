<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Dto;

interface OutputDtoInterface
{
    /**
     * Converts DTO into associative array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
