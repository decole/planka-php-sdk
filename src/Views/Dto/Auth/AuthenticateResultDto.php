<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Auth;

final class AuthenticateResultDto
{
    public const CHALLENGE_TOTP = 'totp';
    public const CHALLENGE_TERMS = 'terms';

    /**
     * @param array<string, mixed> $_rawResponse
     */
    public function __construct(
        public readonly bool $success,
        public readonly ?string $token = null,
        public readonly ?string $pendingToken = null,
        public readonly ?string $challenge = null,
        public readonly array $_rawResponse = [],
    ) {}

    public function requiresTotp(): bool
    {
        return self::CHALLENGE_TOTP === $this->challenge;
    }

    public function requiresTerms(): bool
    {
        return self::CHALLENGE_TERMS === $this->challenge;
    }
}
