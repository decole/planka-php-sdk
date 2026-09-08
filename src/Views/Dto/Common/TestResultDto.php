<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Common;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

final class TestResultDto implements OutputDtoInterface
{
    public function __construct(
        public readonly bool $success = true,
        public readonly ?string $message = null,
        public readonly array $_rawResponse = [],
    ) {}
}
