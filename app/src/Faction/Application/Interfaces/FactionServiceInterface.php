<?php

namespace App\Faction\Application\Interfaces;

use App\Faction\Domain\Exceptions\FactionNotFoundException;
use App\Faction\Domain\Faction;

interface FactionServiceInterface
{
    /**
     * @throws FactionNotFoundException
     */
    public function getFaction(int $id): Faction;

    /**
     * @throws FactionNotFoundException
     */
    public function getFactions(): array;

}