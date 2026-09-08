<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Common;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Common\ServerInfoDto;

final class ServerInfoDtoFactory implements OutputInterface
{
    public function create(array $data): ServerInfoDto
    {
        $statusCode = (int) ($data['statusCode'] ?? $data['code'] ?? $data['status'] ?? 200);

        return new ServerInfoDto(
            statusCode: $statusCode,
            _rawResponse: $data,
        );
    }
}
