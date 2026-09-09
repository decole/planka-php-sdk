<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Common;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

final class BootstrapDto implements OutputDtoInterface
{
    /**
     * @param array{
     *   authorizationUrl?: ?string,
     *   endSessionUrl?: ?string,
     *   isEnforced?: bool
     * }|null $oidc
     * @param array<string, mixed> $_rawResponse diagnostic raw response array from Planka API to verify DTO field hydration
     */
    public function __construct(
        public readonly ?string $version = null,
        public readonly ?array $oidc = null,
        public readonly ?int $activeUsersLimit = null,
        public readonly ?string $customerPanelUrl = null,
        public readonly ?array $termsLanguages = null,
        public readonly array $_rawResponse = [],
    ) {}
}
