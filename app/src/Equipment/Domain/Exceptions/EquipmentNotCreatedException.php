<?php

namespace App\Equipment\Domain\Exceptions;

use Exception;

class EquipmentNotCreatedException extends Exception
{
    private const DETAIL_NOT_CREATED = 'Equipment not created: %s';

    public static function fromName(string $name): self
    {
        return new self(sprintf(self::DETAIL_NOT_CREATED, $name));
    }

}