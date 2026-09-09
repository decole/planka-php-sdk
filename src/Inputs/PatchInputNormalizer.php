<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Config;

final class PatchInputNormalizer
{
    /**
     * @param array<string, mixed>|PatchInputInterface $map
     *
     * @return array<string, mixed>
     */
    public static function normalize(array|PatchInputInterface $map): array
    {
        $data = $map instanceof PatchInputInterface ? $map->toArray() : $map;

        foreach ($data as $key => $value) {
            if ($value instanceof \BackedEnum) {
                $data[$key] = $value->value;
            } elseif ($value instanceof \DateTimeInterface) {
                $data[$key] = $value->format(Config::DATE_FORMAT);
            }
        }

        return $data;
    }
}
