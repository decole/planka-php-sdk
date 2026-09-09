<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\SystemConfig;

use Planka\Bridge\Views\Dto\Config\ConfigDto;

/**
 * Represents system configuration settings.
 *
 * This DTO extends ConfigDto and is structurally identical to it because
 * OpenAPI (swagger.json) defines a single 'Config' schema. It exists to
 * maintain SDK-wide type consistency and Output Factory conventions.
 */
class SystemConfigDto extends ConfigDto {}
