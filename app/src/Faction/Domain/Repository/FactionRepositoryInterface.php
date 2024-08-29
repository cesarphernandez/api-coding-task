<?php

namespace App\Faction\Domain\Repository;

use App\Faction\Domain\Exceptions\FactionNotFoundException;
use App\Faction\Domain\Faction;

interface FactionRepositoryInterface
{

    /**
     * @throws FactionNotFoundException
     */
    public function getFaction(int $id): Faction;

    /**
     * @return array<string, Faction>
     * @throws FactionNotFoundException
     */
    public function getFactions(): array;

    public function createFaction(Faction $faction): Faction;

    public function updateFaction(int $id, Faction $faction): Faction;

    public function deleteFaction(int $id): bool;

}