<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Config;

trait DateConverterTrait
{
    final public function convertToDateTime(?string $date): ?\DateTimeImmutable
    {
        if (null === $date) {
            return null;
        }

        return \DateTimeImmutable::createFromFormat(Config::DATE_FORMAT, $date);
    }
}
