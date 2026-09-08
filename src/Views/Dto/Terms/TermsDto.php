<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Terms;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class TermsDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?string $language = null,
        public readonly ?string $content = null,
        public readonly ?string $signature = null,
        public readonly array $_rawResponse = [],
    ) {}
}
