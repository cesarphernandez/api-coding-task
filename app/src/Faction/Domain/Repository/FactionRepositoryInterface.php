<?php

namespace App\Faction\Domain\Repository;

use App\Faction\Domain\Faction;

interface FactionRepositoryInterface
{

    public function getFaction(int $id): Faction;

    public function getFactions(): array;

    public function createFaction(Faction $faction): Faction;

    public function updateFaction(int $id, Faction $faction): Faction;

    public function deleteFaction(int $id): bool;

}