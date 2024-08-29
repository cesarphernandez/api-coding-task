<?php

namespace App\Faction\Domain\Factory;

use App\Faction\Domain\Faction;

class FactionFactory
{
    public function create(array $faction): Faction
    {
        return new Faction(
            $faction['faction_name'],
            $faction['description'],
            $faction['id'] ?? 0
        );
    }

}