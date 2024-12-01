<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

trait DateConverterTrait
{
    final public function convertToDateTime(?string $date): ?\DateTimeImmutable
    {
        if (null === $date) {
            return null;
        }

        return \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $date);
    }
}
