<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Terms;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

class TermsDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly ?string $language = null,
        public readonly ?string $content = null,
        public readonly ?string $signature = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
