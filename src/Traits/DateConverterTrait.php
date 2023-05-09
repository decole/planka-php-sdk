<?php

namespace Planka\Bridge\Traits;

use DateTimeImmutable;
use DateTimeInterface;

trait DateConverterTrait
{
    final public function convertToDateTime(?string $date): ?DateTimeImmutable
    {
        if ($date === null) {
            return null;
        }

        return DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $date);
    }
}