<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

trait OutputDtoTrait
{
    /**
     * Converts DTO into associative array representation.
     *
     * @return array<string, mixed>
     */
    final public function toArray(): array
    {
        if (property_exists($this, '_rawResponse') && is_array($this->_rawResponse) && !empty($this->_rawResponse)) {
            return $this->_rawResponse;
        }

        return get_object_vars($this);
    }
}
