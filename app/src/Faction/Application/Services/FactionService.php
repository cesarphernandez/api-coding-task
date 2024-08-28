<?php

namespace App\Faction\Application\Services;

use App\Faction\Application\DTO\CreateFactionDTO;
use App\Faction\Domain\Faction;
use App\Faction\Domain\Factory\FactionFactory;
use App\Faction\Domain\Repository\FactionRepositoryInterface;
use function DI\create;

class FactionService
{
    private FactionFactory $factionFactory;
    public function __construct(
        private FactionRepositoryInterface $factionRepository
    ) {
        $this->factionFactory = new FactionFactory();
    }

    public function getFaction(int $id): Faction
    {
        return $this->factionRepository->getFaction($id);
    }

    public function createFaction(CreateFactionDTO $factionDTO): Faction
    {
        $faction = $this->factionFromDTO($factionDTO);

        return $this->factionRepository->createFaction($faction);
    }

    public function updateFaction(int $id, array $faction): Faction
    {
        return $this->factionRepository->updateFaction($id, $faction);
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