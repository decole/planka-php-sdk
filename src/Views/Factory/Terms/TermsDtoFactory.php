<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Terms;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Terms\TermsDto;

final class TermsDtoFactory implements OutputInterface
{
    public function create(array $data): TermsDto
    {
        $item = $data['item'] ?? $data;

        return new TermsDto(
            language: is_string($item['language'] ?? null) ? $item['language'] : null,
            content: is_string($item['content'] ?? null) ? $item['content'] : null,
            signature: is_string($item['signature'] ?? null) ? $item['signature'] : null,
            _rawResponse: $data,
        );
    }
}
