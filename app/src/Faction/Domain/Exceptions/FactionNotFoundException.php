<?php

namespace App\Faction\Domain\Exceptions;

use Exception;

class FactionNotFoundException extends Exception
{
    private const DETAIL_NOT_FOUND = 'Faction not found: with id %s';

    public static function fromId(string $id): self
    {
        return new self(sprintf(self::DETAIL_NOT_FOUND, $id));

    }

    public static function fromAll(): self
    {
        return new self('Factions not found');
    }

}