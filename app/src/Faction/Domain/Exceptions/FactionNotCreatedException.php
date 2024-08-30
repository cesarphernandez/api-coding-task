<?php

namespace App\Faction\Domain\Exceptions;

use App\Faction\Domain\Faction;
use Exception;

class FactionNotCreatedException extends Exception
{
    private const DETAIL_NOT_CREATED = 'Faction not created: %s';

    public static function fromFaction(Faction $faction ): self
    {
        return new self(sprintf(self::DETAIL_NOT_CREATED, $faction->getName()));

    }
}