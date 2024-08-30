<?php

declare(strict_types=1);

namespace App\Faction\Application\Services;

use App\Faction\Application\Interfaces\FactionServiceInterface;
use App\Faction\Domain\Faction;
use Predis\Client;

final class CacheFactionService implements FactionServiceInterface
{
    public function __construct(
        readonly private FactionService $factionService,
        readonly private Client $redisClient,
        readonly private int $ttl
    ) {
    }

    public function getFaction(int $id): Faction
    {
        $faction = $this->redisClient->get("faction:$id");

        if ($faction === null) {
            $faction = $this->factionService->getFaction($id);
            $this->redisClient->set("faction:$id", serialize($faction), 'EX', $this->ttl);
        } else {
            $faction = unserialize($faction);
        }

        return $faction;
    }

    public function getFactions(): array
    {
        $factions = $this->redisClient->get("factions");
        if ($factions === null) {
            $factions = $this->factionService->getFactions();
            $this->redisClient->set("factions", serialize($factions), 'EX', $this->ttl);
        } else {
            $factions = unserialize($factions);
        }

        return $factions;
    }
}