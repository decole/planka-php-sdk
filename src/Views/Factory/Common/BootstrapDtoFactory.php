<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Common;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Common\BootstrapDto;

final class BootstrapDtoFactory implements OutputInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     *      array{
     *          version?: ?string,
     *          oidc?: ?array{authorizationUrl?: ?string, endSessionUrl?: ?string, isEnforced?: bool},
     *          activeUsersLimit?: ?int,
     *          customerPanelUrl?: ?string,
     *          termsLanguages?: ?string
     *      }
     */
    public function create(array $data): BootstrapDto
    {
        $item = $data['item'] ?? $data;

        return new BootstrapDto(
            version: isset($item['version']) && is_string($item['version']) ? $item['version'] : null,
            oidc: isset($item['oidc']) && is_array($item['oidc']) ? $item['oidc'] : null,
            activeUsersLimit: isset($item['activeUsersLimit']) ? (int) $item['activeUsersLimit'] : null,
            customerPanelUrl: isset($item['customerPanelUrl']) && is_string($item['customerPanelUrl']) ? $item['customerPanelUrl'] : null,
            termsLanguages: isset($item['termsLanguages']) && is_array($item['termsLanguages']) ? $item['termsLanguages'] : null,
            _rawResponse: $data,
        );
    }
}
