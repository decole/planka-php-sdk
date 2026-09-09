<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

class StopWatchDto
{
    public function __construct(
        public ?\DateTimeImmutable $startedAt,
        public int $total,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
