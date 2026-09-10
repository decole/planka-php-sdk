<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Enum\LanguageEnum;
use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;
use Planka\Bridge\Views\Dto\Terms\TermsDto;

interface TermsResourceInterface
{
    public function get(?LanguageEnum $language = null): TermsDto;

    public function acceptTerms(
        string $pendingToken,
        string $signature,
        ?LanguageEnum $initialLanguage = null,
    ): AccessTokenDto;
}
