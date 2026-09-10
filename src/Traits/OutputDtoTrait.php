<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

trait OutputDtoTrait
{
    /**
     * Converts DTO into associative array representation.
     *
     * @return array<string, mixed>
     */
    final public function toArray(): array
    {
        $vars = get_object_vars($this);
        unset($vars['_rawResponse']);

        $result = [];

        /** @var mixed $value */
        foreach ($vars as $key => $value) {
            if ($value instanceof OutputDtoInterface) {
                $result[$key] = $value->toArray();
            } elseif ($value instanceof \DateTimeInterface) {
                $result[$key] = $value->format(Config::DATE_FORMAT);
            } elseif (is_object($value) && property_exists($value, 'value')) {
                $result[$key] = $value->value;
            } elseif (is_array($value)) {
                $result[$key] = array_map(
                    static function (mixed $item): mixed {
                        return $item instanceof OutputDtoInterface ? $item->toArray() : $item;
                    },
                    $value,
                );
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
