<?php

namespace App\Faction\Application\Services;

use App\Faction\Application\DTO\CreateFactionDTO;
use App\Faction\Application\DTO\PartialFactionDTO;
use App\Faction\Application\Interfaces\FactionServiceInterface;
use App\Faction\Domain\Exceptions\FactionNotCreatedException;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use App\Faction\Domain\Faction;
use App\Faction\Domain\Factory\FactionFactory;
use App\Faction\Domain\Repository\FactionRepositoryInterface;

class FactionService  implements FactionServiceInterface
{
    private FactionFactory $factionFactory;
    public function __construct(
        private FactionRepositoryInterface $factionRepository
    ) {
        $this->factionFactory = new FactionFactory();
    }

    /**
     * @throws FactionNotFoundException
     */
    public function getFaction(int $id): Faction
    {
        return $this->factionRepository->getFaction($id);
    }

    /**
     * @throws FactionNotFoundException
     */
    public function getFactions(): array
    {
        $factions = $this->factionRepository->getFactions();
        $data = [];
        foreach ($factions as $faction) {
            $data[] = $faction->toArray();
        }
        return $data;
    }

    /**
     * @throws FactionNotCreatedException
     */
    public function createFaction(CreateFactionDTO $factionDTO): Faction
    {
        $faction = $this->factionFromDTO($factionDTO);

        return $this->factionRepository->createFaction($faction);
    }

    /**
     * @throws FactionNotFoundException
     */
    public function updateFaction(int $id, PartialFactionDTO $faction): Faction
    {
        return $this->factionRepository->updateFaction($id, [
            'faction_name' => $faction->getName(),
            'description' => $faction->getDescription()
        ]);
    }

    public function deleteFaction(int $id): bool
    {
        return $this->factionRepository->deleteFaction($id);
    }

    private function factionFromDTO(CreateFactionDTO $factionDTO): Faction
    {
        return $this->factionFactory->create([
            'faction_name' => $factionDTO->getName(),
            'description' => $factionDTO->getDescription()
        ]);
    }

}