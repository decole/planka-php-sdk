<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Auth\AcceptTermsAction;
use Planka\Bridge\Actions\Auth\GetTermsAction;
use Planka\Bridge\Enum\LanguageEnum;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;
use Planka\Bridge\Views\Dto\Terms\TermsDto;

final class Terms
{
    public function __construct(private readonly Client $client) {}

    /** 'GET /api/terms' */
    public function get(?LanguageEnum $language = null): TermsDto
    {
        return $this->client->get(new GetTermsAction($language));
    }

    /** 'POST /api/access-tokens/accept-terms' */
    public function acceptTerms(string $pendingToken, string $signature, ?LanguageEnum $initialLanguage = null): AccessTokenDto
    {
        return $this->client->post(new AcceptTermsAction($pendingToken, $signature, $initialLanguage));
    }
}
