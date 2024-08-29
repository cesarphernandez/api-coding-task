<?php

namespace App\Faction\Domain\Repository;

use App\Faction\Domain\Exceptions\FactionNotCreatedException;
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

    /**
     * @throws FactionNotCreatedException
     */
    public function createFaction(Faction $faction): Faction;

    /**
     * @throws FactionNotFoundException
     */
    public function updateFaction(int $id, array $faction): Faction;

    public function deleteFaction(int $id): bool;

}