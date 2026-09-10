<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Config;

trait DateConverterTrait
{
    final public function convertToDateTime(?string $date): ?\DateTimeImmutable
    {
        if (null === $date || '' === $date) {
            return null;
        }

        $parsed = \DateTimeImmutable::createFromFormat(Config::DATE_FORMAT, $date);

        if (false !== $parsed) {
            return $parsed;
        }

        try {
            return new \DateTimeImmutable($date);
        } catch (\Throwable) {
            return null;
        }
    }
}
