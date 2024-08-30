<?php

namespace App\Equipment\Domain\Exceptions;

use Exception;

class EquipmentNotFoundException extends Exception
{
    private const DETAIL_NOT_FOUND = 'Equipment not found: %s';

    public static function fromId(int $id): self
    {
        return new self(sprintf(self::DETAIL_NOT_FOUND, $id));
    }

}